import 'package:flutter/material.dart';
import '../../config/app_colors.dart';
import '../../config/constants.dart';
import '../../widgets/common/app_logo.dart';
import '../../widgets/common/rounded_card.dart';

class AboutScreen extends StatelessWidget {
  const AboutScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(title: const Text('About')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            const AppLogo(size: 80),
            const SizedBox(height: 8),
            const Text(
              'Version 1.0.0',
              style: TextStyle(color: AppColors.accentLight),
            ),
            const SizedBox(height: 32),
            RoundedCard(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'About Blood Map PH',
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: AppColors.accent,
                    ),
                  ),
                  const SizedBox(height: 12),
                  Text(
                    '${AppConstants.appName} is a comprehensive blood bank inventory and emergency request system designed for the Philippines. Our mission is to connect blood donors, recipients, and blood banks in real time to save lives.',
                    style: TextStyle(
                      fontSize: 14,
                      color: AppColors.accentLight.withValues(alpha: 0.9),
                      height: 1.6,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 16),
            RoundedCard(
              child: Column(
                children: [
                  _AboutTile(
                    icon: Icons.email_outlined,
                    label: 'Email',
                    value: 'support@bloodmapph.com',
                  ),
                  const Divider(),
                  _AboutTile(
                    icon: Icons.phone_outlined,
                    label: 'Hotline',
                    value: '+63 2 8123 4567',
                  ),
                  const Divider(),
                  _AboutTile(
                    icon: Icons.language,
                    label: 'Website',
                    value: 'www.bloodmapph.com',
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _AboutTile extends StatelessWidget {
  const _AboutTile({
    required this.icon,
    required this.label,
    required this.value,
  });

  final IconData icon;
  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          Icon(icon, color: AppColors.primary, size: 22),
          const SizedBox(width: 14),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(label,
                  style: const TextStyle(
                      fontSize: 12, color: AppColors.accentLight)),
              Text(value,
                  style: const TextStyle(
                      fontSize: 15,
                      fontWeight: FontWeight.w500,
                      color: AppColors.accent)),
            ],
          ),
        ],
      ),
    );
  }
}
