import sharp from 'sharp';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const imagesDir = path.join(__dirname, '../public/images');
const CANVAS_SIZE = 2048;
const PADDING = 40;

function colorDistance(r1, g1, b1, r2, g2, b2) {
    return Math.sqrt((r1 - r2) ** 2 + (g1 - g2) ** 2 + (b1 - b2) ** 2);
}

function isBackgroundPixel(r, g, b, mode) {
    if (mode === 'white') {
        const brightness = (r + g + b) / 3;
        const spread = Math.max(r, g, b) - Math.min(r, g, b);
        return brightness > 238 && spread < 12;
    }

    const brightness = (r + g + b) / 3;
    const spread = Math.max(r, g, b) - Math.min(r, g, b);
    return brightness < 22 && spread < 16;
}

function floodRemoveBackground(data, width, height, mode) {
    const visited = new Uint8Array(width * height);
    const queue = [];

    const pushIfBackground = (x, y) => {
        const idx = y * width + x;
        if (visited[idx]) return;
        const offset = idx * 4;
        const r = data[offset];
        const g = data[offset + 1];
        const b = data[offset + 2];
        if (!isBackgroundPixel(r, g, b, mode)) return;
        visited[idx] = 1;
        queue.push(idx);
    };

    for (let x = 0; x < width; x++) {
        pushIfBackground(x, 0);
        pushIfBackground(x, height - 1);
    }
    for (let y = 0; y < height; y++) {
        pushIfBackground(0, y);
        pushIfBackground(width - 1, y);
    }

    while (queue.length) {
        const idx = queue.pop();
        const x = idx % width;
        const y = (idx - x) / width;
        const offset = idx * 4;
        const r = data[offset];
        const g = data[offset + 1];
        const b = data[offset + 2];

        data[offset + 3] = 0;

        const neighbors = [
            [x - 1, y],
            [x + 1, y],
            [x, y - 1],
            [x, y + 1],
        ];

        for (const [nx, ny] of neighbors) {
            if (nx < 0 || ny < 0 || nx >= width || ny >= height) continue;
            const nIdx = ny * width + nx;
            if (visited[nIdx]) continue;
            const nOffset = nIdx * 4;
            const nr = data[nOffset];
            const ng = data[nOffset + 1];
            const nb = data[nOffset + 2];

            const similar =
                colorDistance(r, g, b, nr, ng, nb) < (mode === 'white' ? 28 : 24) &&
                isBackgroundPixel(nr, ng, nb, mode);

            if (similar) {
                visited[nIdx] = 1;
                queue.push(nIdx);
            }
        }
    }

    // Soft edge anti-aliasing near transparent pixels
    for (let y = 1; y < height - 1; y++) {
        for (let x = 1; x < width - 1; x++) {
            const idx = y * width + x;
            const offset = idx * 4;
            if (data[offset + 3] === 0) continue;

            let transparentNeighbors = 0;
            for (let dy = -1; dy <= 1; dy++) {
                for (let dx = -1; dx <= 1; dx++) {
                    if (!dx && !dy) continue;
                    const nIdx = (y + dy) * width + (x + dx);
                    if (data[nIdx * 4 + 3] === 0) transparentNeighbors++;
                }
            }

            if (transparentNeighbors >= 3) {
                const softness = Math.min(255, transparentNeighbors * 42);
                data[offset + 3] = Math.max(0, 255 - softness);
            }
        }
    }
}

function getBounds(data, width, height) {
    let minX = width;
    let minY = height;
    let maxX = 0;
    let maxY = 0;

    for (let y = 0; y < height; y++) {
        for (let x = 0; x < width; x++) {
            const alpha = data[(y * width + x) * 4 + 3];
            if (alpha > 16) {
                minX = Math.min(minX, x);
                minY = Math.min(minY, y);
                maxX = Math.max(maxX, x);
                maxY = Math.max(maxY, y);
            }
        }
    }

    return { minX, minY, maxX, maxY };
}

async function processImage(filename, mode, targetSize) {
    const inputPath = path.join(imagesDir, filename);
    const { data, info } = await sharp(inputPath)
        .resize(targetSize.width, targetSize.height, { fit: 'fill', kernel: sharp.kernel.lanczos3 })
        .ensureAlpha()
        .raw()
        .toBuffer({ resolveWithObject: true });

    const rgba = Buffer.from(data);
    floodRemoveBackground(rgba, info.width, info.height, mode);

    return {
        rgba,
        width: info.width,
        height: info.height,
    };
}

function getSharedBounds(emptyRgba, filledRgba, width, height) {
    let minX = width;
    let minY = height;
    let maxX = 0;
    let maxY = 0;

    for (const data of [emptyRgba, filledRgba]) {
        for (let y = 0; y < height; y++) {
            for (let x = 0; x < width; x++) {
                const alpha = data[(y * width + x) * 4 + 3];
                if (alpha > 16) {
                    minX = Math.min(minX, x);
                    minY = Math.min(minY, y);
                    maxX = Math.max(maxX, x);
                    maxY = Math.max(maxY, y);
                }
            }
        }
    }

    return { minX, minY, maxX, maxY };
}

async function cropToBounds(rgba, width, height, bounds) {
    const cropWidth = bounds.maxX - bounds.minX + 1;
    const cropHeight = bounds.maxY - bounds.minY + 1;

    return sharp(rgba, { raw: { width, height, channels: 4 } })
        .extract({
            left: bounds.minX,
            top: bounds.minY,
            width: cropWidth,
            height: cropHeight,
        })
        .png()
        .toBuffer()
        .then((buffer) => ({ buffer, cropWidth, cropHeight }));
}

async function placeOnCanvas(empty, filled) {
    const maxCropW = Math.max(empty.cropWidth, filled.cropWidth);
    const maxCropH = Math.max(empty.cropHeight, filled.cropHeight);

    const scale = Math.min(
        (CANVAS_SIZE - PADDING * 2) / maxCropW,
        (CANVAS_SIZE - PADDING * 2) / maxCropH
    );

    const targetW = Math.round(maxCropW * scale);
    const targetH = Math.round(maxCropH * scale);
    const offsetX = Math.round((CANVAS_SIZE - targetW) / 2);
    const offsetY = Math.round((CANVAS_SIZE - targetH) / 2);

    async function composeAligned(item, label) {
        const resized = await sharp(item.buffer)
            .resize({
                width: Math.round(item.cropWidth * scale),
                height: Math.round(item.cropHeight * scale),
                fit: 'fill',
                kernel: sharp.kernel.lanczos3,
            })
            .png()
            .toBuffer();

        const meta = await sharp(resized).metadata();
        const left = offsetX + Math.round((targetW - meta.width) / 2);
        const top = offsetY + Math.round((targetH - meta.height) / 2);

        const outPath = path.join(imagesDir, label);
        await sharp({
            create: {
                width: CANVAS_SIZE,
                height: CANVAS_SIZE,
                channels: 4,
                background: { r: 0, g: 0, b: 0, alpha: 0 },
            },
        })
            .composite([{ input: resized, left, top }])
            .png({ compressionLevel: 6, adaptiveFiltering: true })
            .toFile(outPath);

        return outPath;
    }

    return {
        emptyPath: await composeAligned(empty, 'blood-bag-empty.png'),
        filledPath: await composeAligned(filled, 'blood-bag-filled.png'),
        scale,
    };
}

const targetSize = { width: 604, height: 957 };
const emptyRaw = await processImage('WT.jpg', 'white', targetSize);
const filledRaw = await processImage('BK.jpg', 'black', targetSize);
const sharedBounds = getSharedBounds(emptyRaw.rgba, filledRaw.rgba, targetSize.width, targetSize.height);
const empty = await cropToBounds(emptyRaw.rgba, targetSize.width, targetSize.height, sharedBounds);
const filled = await cropToBounds(filledRaw.rgba, targetSize.width, targetSize.height, sharedBounds);
const result = await placeOnCanvas(empty, filled);

console.log('Created transparent PNGs:');
console.log(result.emptyPath);
console.log(result.filledPath);
console.log({
    emptyCrop: `${empty.cropWidth}x${empty.cropHeight}`,
    filledCrop: `${filled.cropWidth}x${filled.cropHeight}`,
    canvas: `${CANVAS_SIZE}x${CANVAS_SIZE}`,
    scale: result.scale,
});
