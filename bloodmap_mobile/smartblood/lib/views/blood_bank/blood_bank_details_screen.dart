import 'package:flutter/material.dart';
import '../../config/app_colors.dart';
import '../../data/mock/mock_data.dart';
import '../../widgets/common/blood_type_chip.dart';
import '../../widgets/common/primary_button.dart';
import '../../widgets/common/rounded_card.dart';

class BloodBankDetailsScreen extends StatelessWidget {
  const BloodBankDetailsScreen({super.key, required this.id});

  final String id;

  @override
  Widget build(BuildContext context) {
    final bank = MockData.bloodBanks.firstWhere(
      (b) => b.id == id,
      orElse: () => MockData.bloodBanks.first,
    );

    return Scaffold(
      backgroundColor: AppColors.background,
      body: CustomScrollView(
        slivers: [
          SliverAppBar(
            expandedHeight: 200,
            pinned: true,
            flexibleSpace: FlexibleSpaceBar(
              title: Text(
                bank.name,
                style: const TextStyle(fontSize: 16),
              ),
              background: Container(
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    colors: [AppColors.primary, AppColors.primaryDark],
                  ),
                ),
                child: const Center(
                  child: Icon(
                    Icons.local_hospital,
                    size: 64,
                    color: Colors.white54,
                  ),
                ),
              ),
            ),
          ),
          SliverPadding(
            padding: const EdgeInsets.all(20),
            sliver: SliverList(
              delegate: SliverChildListDelegate([
                RoundedCard(
                  child: Column(
                    children: [
                      _InfoTile(
                        icon: Icons.location_on_outlined,
                        label: 'Address',
                        value: bank.address,
                      ),
                      const Divider(),
                      _InfoTile(
                        icon: Icons.phone_outlined,
                        label: 'Phone',
                        value: bank.phone,
                      ),
                      const Divider(),
                      _InfoTile(
                        icon: Icons.straighten,
                        label: 'Distance',
                        value: bank.distance,
                      ),
                      const Divider(),
                      _InfoTile(
                        icon: Icons.star,
                        label: 'Rating',
                        value: '${bank.rating} / 5.0',
                      ),
                      const Divider(),
                      _InfoTile(
                        icon: Icons.access_time,
                        label: 'Status',
                        value: bank.isOpen ? 'Open Now' : 'Closed',
                        valueColor:
                            bank.isOpen ? AppColors.success : AppColors.accentLight,
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 24),
                const Text(
                  'Blood Inventory',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: AppColors.accent,
                  ),
                ),
                const SizedBox(height: 12),
                RoundedCard(
                  child: Wrap(
                    spacing: 10,
                    runSpacing: 10,
                    children: bank.inventory.entries.map((e) {
                      return BloodTypeChip(
                        bloodType: '${e.key}: ${e.value} units',
                      );
                    }).toList(),
                  ),
                ),
                const SizedBox(height: 24),
                PrimaryButton(
                  label: 'Request Blood',
                  icon: Icons.bloodtype,
                  onPressed: () {},
                ),
                const SizedBox(height: 12),
                OutlinedButton.icon(
                  onPressed: () {},
                  icon: const Icon(Icons.directions),
                  label: const Text('Get Directions'),
                  style: OutlinedButton.styleFrom(
                    minimumSize: const Size(double.infinity, 52),
                    foregroundColor: AppColors.primary,
                    side: const BorderSide(color: AppColors.primary),
                    shape: const StadiumBorder(),
                  ),
                ),
                const SizedBox(height: 12),
                OutlinedButton.icon(
                  onPressed: () {},
                  icon: const Icon(Icons.phone),
                  label: const Text('Call Blood Bank'),
                  style: OutlinedButton.styleFrom(
                    minimumSize: const Size(double.infinity, 52),
                    foregroundColor: AppColors.primary,
                    side: const BorderSide(color: AppColors.primary),
                    shape: const StadiumBorder(),
                  ),
                ),
              ]),
            ),
          ),
        ],
      ),
    );
  }
}

class _InfoTile extends StatelessWidget {
  const _InfoTile({
    required this.icon,
    required this.label,
    required this.value,
    this.valueColor,
  });

  final IconData icon;
  final String label;
  final String value;
  final Color? valueColor;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          Icon(icon, color: AppColors.primary, size: 22),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  label,
                  style: const TextStyle(
                    fontSize: 12,
                    color: AppColors.accentLight,
                  ),
                ),
                Text(
                  value,
                  style: TextStyle(
                    fontSize: 15,
                    fontWeight: FontWeight.w500,
                    color: valueColor ?? AppColors.accent,
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
