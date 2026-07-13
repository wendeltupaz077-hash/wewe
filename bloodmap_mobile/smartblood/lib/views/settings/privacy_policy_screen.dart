import 'package:flutter/material.dart';
import '../../config/app_colors.dart';

class PrivacyPolicyScreen extends StatelessWidget {
  const PrivacyPolicyScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(title: const Text('Privacy Policy')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Last updated: July 10, 2026',
              style: TextStyle(color: AppColors.accentLight, fontSize: 13),
            ),
            const SizedBox(height: 24),
            ..._sections.map((section) => Padding(
                  padding: const EdgeInsets.only(bottom: 24),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        section.title,
                        style: const TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: AppColors.accent,
                        ),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        section.content,
                        style: const TextStyle(
                          fontSize: 14,
                          color: AppColors.accentLight,
                          height: 1.6,
                        ),
                      ),
                    ],
                  ),
                )),
          ],
        ),
      ),
    );
  }
}

class _PolicySection {
  const _PolicySection({required this.title, required this.content});

  final String title;
  final String content;
}

const _sections = [
  _PolicySection(
    title: 'Information We Collect',
    content:
        'Blood Map PH collects personal information you provide during registration, including your name, email, phone number, blood type, and location data. We also collect usage data to improve our services.',
  ),
  _PolicySection(
    title: 'How We Use Your Information',
    content:
        'Your information is used to facilitate blood requests, connect you with nearby donors and blood banks, send emergency alerts, and maintain your donation and request history. We never sell your personal data.',
  ),
  _PolicySection(
    title: 'Data Security',
    content:
        'We implement industry-standard security measures to protect your personal information. All data transmissions are encrypted, and access to personal data is restricted to authorized personnel only.',
  ),
  _PolicySection(
    title: 'Location Data',
    content:
        'With your consent, we collect location data to show nearby blood banks and donors. You can disable location services at any time in the app settings.',
  ),
  _PolicySection(
    title: 'Your Rights',
    content:
        'You have the right to access, update, or delete your personal information. Contact us at privacy@bloodmapph.com for any data-related requests.',
  ),
  _PolicySection(
    title: 'Contact Us',
    content:
        'If you have questions about this Privacy Policy, please contact us at privacy@bloodmapph.com or call our hotline at +63 2 8123 4567.',
  ),
];
