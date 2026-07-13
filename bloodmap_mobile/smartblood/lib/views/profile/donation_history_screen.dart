import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../../config/app_colors.dart';
import '../../viewmodels/profile_viewmodel.dart';
import '../../widgets/common/empty_state.dart';
import '../../widgets/common/rounded_card.dart';

class DonationHistoryScreen extends StatelessWidget {
  const DonationHistoryScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final history = context.watch<ProfileViewModel>().donationHistory;
    final dateFormat = DateFormat('MMMM d, yyyy');

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(title: const Text('Donation History')),
      body: history.isEmpty
          ? const EmptyState(
              icon: Icons.volunteer_activism,
              title: 'No donations yet',
              subtitle: 'Your donation history will appear here.',
            )
          : ListView.builder(
              padding: const EdgeInsets.all(20),
              itemCount: history.length,
              itemBuilder: (context, index) {
                final record = history[index];
                return RoundedCard(
                  margin: const EdgeInsets.only(bottom: 12),
                  child: Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(12),
                        decoration: BoxDecoration(
                          color: AppColors.primary.withValues(alpha: 0.1),
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: const Icon(
                          Icons.bloodtype,
                          color: AppColors.primary,
                        ),
                      ),
                      const SizedBox(width: 16),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              record.bloodType,
                              style: const TextStyle(
                                fontWeight: FontWeight.bold,
                                fontSize: 16,
                                color: AppColors.accent,
                              ),
                            ),
                            Text(
                              record.location,
                              style: const TextStyle(
                                fontSize: 13,
                                color: AppColors.accentLight,
                              ),
                            ),
                            Text(
                              dateFormat.format(record.date),
                              style: const TextStyle(
                                fontSize: 12,
                                color: AppColors.accentLight,
                              ),
                            ),
                          ],
                        ),
                      ),
                      Text(
                        '${record.units} unit',
                        style: const TextStyle(
                          fontWeight: FontWeight.w600,
                          color: AppColors.success,
                        ),
                      ),
                    ],
                  ),
                );
              },
            ),
    );
  }
}
