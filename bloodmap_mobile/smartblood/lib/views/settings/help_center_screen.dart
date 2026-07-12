import 'package:flutter/material.dart';
import '../../config/app_colors.dart';
import '../../widgets/common/rounded_card.dart';

class HelpCenterScreen extends StatefulWidget {
  const HelpCenterScreen({super.key});

  @override
  State<HelpCenterScreen> createState() => _HelpCenterScreenState();
}

class _HelpCenterScreenState extends State<HelpCenterScreen> {
  final _searchController = TextEditingController();

  final _faqs = [
    _FaqItem(
      question: 'How do I request blood in an emergency?',
      answer:
          'Tap the Emergency Request button on the Home screen, fill in the patient details, select the blood type and units needed, then submit. Nearby donors and blood banks will be notified immediately.',
    ),
    _FaqItem(
      question: 'How can I find nearby blood banks?',
      answer:
          'Go to the Home tab to see nearby blood banks, or use the Map tab to view their locations. You can filter by blood type availability.',
    ),
    _FaqItem(
      question: 'How do I schedule a blood donation?',
      answer:
          'Navigate to Profile > Book Appointment, select a blood bank, choose your preferred date and time, then confirm your booking.',
    ),
    _FaqItem(
      question: 'What is QR Verification?',
      answer:
          'After donating blood, scan the QR code provided by the blood bank to verify and record your donation in your profile history.',
    ),
  ];

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(title: const Text('Help Center')),
      body: ListView(
        padding: const EdgeInsets.all(20),
        children: [
          TextField(
            controller: _searchController,
            decoration: InputDecoration(
              hintText: 'Search for help...',
              prefixIcon: const Icon(Icons.search),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(16),
                borderSide: BorderSide.none,
              ),
              filled: true,
              fillColor: AppColors.surface,
            ),
          ),
          const SizedBox(height: 24),
          const Text(
            'Frequently Asked Questions',
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: AppColors.accent,
            ),
          ),
          const SizedBox(height: 12),
          ..._faqs.map((faq) => RoundedCard(
                margin: const EdgeInsets.only(bottom: 8),
                padding: EdgeInsets.zero,
                child: ExpansionTile(
                  title: Text(
                    faq.question,
                    style: const TextStyle(
                      fontSize: 14,
                      fontWeight: FontWeight.w500,
                      color: AppColors.accent,
                    ),
                  ),
                  iconColor: AppColors.primary,
                  collapsedIconColor: AppColors.accentLight,
                  children: [
                    Padding(
                      padding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
                      child: Text(
                        faq.answer,
                        style: const TextStyle(
                          fontSize: 13,
                          color: AppColors.accentLight,
                          height: 1.5,
                        ),
                      ),
                    ),
                  ],
                ),
              )),
          const SizedBox(height: 24),
          RoundedCard(
            child: Column(
              children: [
                const Icon(Icons.support_agent,
                    size: 48, color: AppColors.primary),
                const SizedBox(height: 12),
                const Text(
                  'Need more help?',
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 4),
                const Text(
                  'Contact our support team',
                  style: TextStyle(color: AppColors.accentLight),
                ),
                const SizedBox(height: 16),
                OutlinedButton.icon(
                  onPressed: () {},
                  icon: const Icon(Icons.email_outlined),
                  label: const Text('Email Support'),
                  style: OutlinedButton.styleFrom(
                    foregroundColor: AppColors.primary,
                    side: const BorderSide(color: AppColors.primary),
                    shape: const StadiumBorder(),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _FaqItem {
  const _FaqItem({required this.question, required this.answer});

  final String question;
  final String answer;
}
