import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../../config/app_colors.dart';
import '../../data/mock/mock_data.dart';
import '../../widgets/common/primary_button.dart';
import '../../widgets/common/rounded_card.dart';

class DonorDetailsScreen extends StatelessWidget {
  const DonorDetailsScreen({super.key, required this.id});

  final String id;

  @override
  Widget build(BuildContext context) {
    final donor = MockData.donors.firstWhere(
      (d) => d.id == id,
      orElse: () => MockData.donors.first,
    );
    final dateFormat = DateFormat('MMMM d, yyyy');

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(title: const Text('Donor Details')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            RoundedCard(
              child: Column(
                children: [
                  CircleAvatar(
                    radius: 40,
                    backgroundColor: AppColors.success.withValues(alpha: 0.12),
                    child: Text(
                      donor.name[0],
                      style: const TextStyle(
                        fontSize: 28,
                        fontWeight: FontWeight.bold,
                        color: AppColors.success,
                      ),
                    ),
                  ),
                  const SizedBox(height: 16),
                  Text(
                    donor.name,
                    style: const TextStyle(
                      fontSize: 22,
                      fontWeight: FontWeight.bold,
                      color: AppColors.accent,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Container(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 16,
                      vertical: 6,
                    ),
                    decoration: BoxDecoration(
                      color: AppColors.primary.withValues(alpha: 0.1),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(
                      donor.bloodType,
                      style: const TextStyle(
                        fontWeight: FontWeight.bold,
                        color: AppColors.primary,
                        fontSize: 16,
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Container(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 12,
                      vertical: 4,
                    ),
                    decoration: BoxDecoration(
                      color: donor.isAvailable
                          ? AppColors.success.withValues(alpha: 0.12)
                          : AppColors.accentLight.withValues(alpha: 0.12),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Text(
                      donor.isAvailable ? 'Available' : 'Unavailable',
                      style: TextStyle(
                        fontWeight: FontWeight.w600,
                        color: donor.isAvailable
                            ? AppColors.success
                            : AppColors.accentLight,
                      ),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),
            RoundedCard(
              child: Column(
                children: [
                  _DetailTile(
                    icon: Icons.location_on_outlined,
                    label: 'Distance',
                    value: donor.distance,
                  ),
                  const Divider(),
                  _DetailTile(
                    icon: Icons.history,
                    label: 'Last Donation',
                    value: dateFormat.format(donor.lastDonation),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 32),
            PrimaryButton(
              label: 'Request Donation',
              icon: Icons.volunteer_activism,
              onPressed: () {},
            ),
            const SizedBox(height: 12),
            OutlinedButton.icon(
              onPressed: () {},
              icon: const Icon(Icons.message_outlined),
              label: const Text('Send Message'),
              style: OutlinedButton.styleFrom(
                minimumSize: const Size(double.infinity, 52),
                foregroundColor: AppColors.primary,
                side: const BorderSide(color: AppColors.primary),
                shape: const StadiumBorder(),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _DetailTile extends StatelessWidget {
  const _DetailTile({
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
          Icon(icon, color: AppColors.primary),
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
