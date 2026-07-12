import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../../config/app_colors.dart';
import '../../widgets/common/empty_state.dart';
import '../../widgets/common/rounded_card.dart';

class DonationScheduleScreen extends StatelessWidget {
  const DonationScheduleScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final schedules = [
      _ScheduleItem(
        date: DateTime.now().add(const Duration(days: 3)),
        time: '10:00 AM',
        location: 'Philippine Red Cross - QC',
        status: 'Confirmed',
      ),
      _ScheduleItem(
        date: DateTime.now().add(const Duration(days: 14)),
        time: '02:00 PM',
        location: 'St. Luke\'s Medical Center',
        status: 'Pending',
      ),
    ];

    final dateFormat = DateFormat('EEEE, MMM d, yyyy');

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(title: const Text('Donation Schedule')),
      body: schedules.isEmpty
          ? const EmptyState(
              icon: Icons.calendar_month,
              title: 'No scheduled donations',
              subtitle: 'Book an appointment to schedule your next donation.',
            )
          : ListView.builder(
              padding: const EdgeInsets.all(20),
              itemCount: schedules.length,
              itemBuilder: (context, index) {
                final schedule = schedules[index];
                final isConfirmed = schedule.status == 'Confirmed';

                return RoundedCard(
                  margin: const EdgeInsets.only(bottom: 12),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          Container(
                            padding: const EdgeInsets.all(10),
                            decoration: BoxDecoration(
                              color: AppColors.primary.withValues(alpha: 0.1),
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: const Icon(
                              Icons.event,
                              color: AppColors.primary,
                            ),
                          ),
                          const SizedBox(width: 14),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  dateFormat.format(schedule.date),
                                  style: const TextStyle(
                                    fontWeight: FontWeight.w600,
                                    color: AppColors.accent,
                                  ),
                                ),
                                Text(
                                  schedule.time,
                                  style: const TextStyle(
                                    fontSize: 13,
                                    color: AppColors.accentLight,
                                  ),
                                ),
                              ],
                            ),
                          ),
                          Container(
                            padding: const EdgeInsets.symmetric(
                              horizontal: 10,
                              vertical: 4,
                            ),
                            decoration: BoxDecoration(
                              color: isConfirmed
                                  ? AppColors.success.withValues(alpha: 0.12)
                                  : AppColors.warning.withValues(alpha: 0.12),
                              borderRadius: BorderRadius.circular(8),
                            ),
                            child: Text(
                              schedule.status,
                              style: TextStyle(
                                fontSize: 12,
                                fontWeight: FontWeight.w600,
                                color: isConfirmed
                                    ? AppColors.success
                                    : AppColors.warning,
                              ),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 12),
                      Row(
                        children: [
                          const Icon(Icons.location_on_outlined,
                              size: 16, color: AppColors.accentLight),
                          const SizedBox(width: 4),
                          Expanded(
                            child: Text(
                              schedule.location,
                              style: const TextStyle(
                                fontSize: 13,
                                color: AppColors.accentLight,
                              ),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                );
              },
            ),
    );
  }
}

class _ScheduleItem {
  const _ScheduleItem({
    required this.date,
    required this.time,
    required this.location,
    required this.status,
  });

  final DateTime date;
  final String time;
  final String location;
  final String status;
}
