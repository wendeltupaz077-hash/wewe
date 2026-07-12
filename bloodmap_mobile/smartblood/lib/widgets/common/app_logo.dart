import 'package:flutter/material.dart';
import '../../config/app_colors.dart';
import '../../config/constants.dart';

class AppLogo extends StatelessWidget {
  const AppLogo({
    super.key,
    this.size = 80,
    this.showText = true,
    this.textSize = 22,
    this.onDarkBackground = false,
    this.splashMode = false,
  });

  static const transparentLogo = 'assets/smartblood_logo.png';
  static const fallbackLogo = 'assets/BLDMP.png';

  final double size;
  final bool showText;
  final double textSize;
  final bool onDarkBackground;
  final bool splashMode;

  @override
  Widget build(BuildContext context) {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        _LogoImage(
          size: size,
          splashMode: splashMode,
        ),
        if (showText) ...[
          const SizedBox(height: 16),
          Text(
            AppConstants.appName,
            style: TextStyle(
              fontSize: textSize,
              fontWeight: FontWeight.bold,
              color: onDarkBackground ? Colors.white : AppColors.accent,
              letterSpacing: -0.5,
            ),
          ),
        ],
      ],
    );
  }
}

class _LogoImage extends StatelessWidget {
  const _LogoImage({
    required this.size,
    required this.splashMode,
  });

  final double size;
  final bool splashMode;

  @override
  Widget build(BuildContext context) {
    final image = Image.asset(
      AppLogo.transparentLogo,
      width: size,
      height: size,
      fit: BoxFit.contain,
      filterQuality: FilterQuality.high,
      gaplessPlayback: true,
      errorBuilder: (_, _, _) => Image.asset(
        AppLogo.fallbackLogo,
        width: size,
        height: size,
        fit: BoxFit.contain,
        filterQuality: FilterQuality.high,
      ),
    );

    if (!splashMode) {
      return image;
    }

    return SizedBox(
      width: size,
      height: size,
      child: FittedBox(
        fit: BoxFit.contain,
        child: image,
      ),
    );
  }
}
