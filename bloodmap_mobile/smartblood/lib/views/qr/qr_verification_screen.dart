import 'package:flutter/material.dart';
import '../../config/app_colors.dart';
import '../../widgets/common/primary_button.dart';
import '../../widgets/common/rounded_card.dart';

class QrVerificationScreen extends StatefulWidget {
  const QrVerificationScreen({super.key});

  @override
  State<QrVerificationScreen> createState() => _QrVerificationScreenState();
}

class _QrVerificationScreenState extends State<QrVerificationScreen> {
  bool _isScanning = false;
  bool _isVerified = false;

  Future<void> _simulateScan() async {
    setState(() => _isScanning = true);
    await Future.delayed(const Duration(seconds: 2));
    if (!mounted) return;
    setState(() {
      _isScanning = false;
      _isVerified = true;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(title: const Text('QR Verification')),
      body: Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            RoundedCard(
              child: Column(
                children: [
                  Container(
                    width: 200,
                    height: 200,
                    decoration: BoxDecoration(
                      color: AppColors.accent.withValues(alpha: 0.05),
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(
                        color: _isVerified
                            ? AppColors.success
                            : AppColors.border,
                        width: 2,
                      ),
                    ),
                    child: _isScanning
                        ? const Center(
                            child: CircularProgressIndicator(
                              color: AppColors.primary,
                            ),
                          )
                        : _isVerified
                            ? const Column(
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: [
                                  Icon(Icons.check_circle,
                                      size: 64, color: AppColors.success),
                                  SizedBox(height: 8),
                                  Text(
                                    'Verified',
                                    style: TextStyle(
                                      fontWeight: FontWeight.bold,
                                      color: AppColors.success,
                                    ),
                                  ),
                                ],
                              )
                            : const Icon(
                                Icons.qr_code_scanner,
                                size: 80,
                                color: AppColors.accentLight,
                              ),
                  ),
                  const SizedBox(height: 20),
                  Text(
                    _isVerified
                        ? 'Donation verified successfully!'
                        : 'Scan the QR code at the blood bank to verify your donation.',
                    textAlign: TextAlign.center,
                    style: const TextStyle(
                      fontSize: 14,
                      color: AppColors.accentLight,
                      height: 1.4,
                    ),
                  ),
                ],
              ),
            ),
            const Spacer(),
            if (!_isVerified)
              PrimaryButton(
                label: _isScanning ? 'Scanning...' : 'Scan QR Code',
                icon: Icons.qr_code_scanner,
                isLoading: _isScanning,
                onPressed: _isScanning ? null : _simulateScan,
              ),
            if (_isVerified) ...[
              RoundedCard(
                color: AppColors.success.withValues(alpha: 0.06),
                child: const Column(
                  children: [
                    _VerifiedDetail(label: 'Donor', value: 'Maria Santos'),
                    Divider(),
                    _VerifiedDetail(label: 'Blood Type', value: 'O+'),
                    Divider(),
                    _VerifiedDetail(
                      label: 'Location',
                      value: 'Philippine Red Cross - QC',
                    ),
                    Divider(),
                    _VerifiedDetail(label: 'Units', value: '1'),
                  ],
                ),
              ),
              const SizedBox(height: 16),
              PrimaryButton(
                label: 'Done',
                onPressed: () => Navigator.of(context).pop(),
              ),
            ],
            const SizedBox(height: 32),
          ],
        ),
      ),
    );
  }
}

class _VerifiedDetail extends StatelessWidget {
  const _VerifiedDetail({required this.label, required this.value});

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 6),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label,
              style: const TextStyle(color: AppColors.accentLight)),
          Text(value,
              style: const TextStyle(
                  fontWeight: FontWeight.w600, color: AppColors.accent)),
        ],
      ),
    );
  }
}
