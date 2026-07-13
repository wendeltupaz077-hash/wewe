import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../config/app_colors.dart';
import '../../config/app_routes.dart';
import '../../viewmodels/profile_viewmodel.dart';
import '../../widgets/common/empty_state.dart';
import '../../widgets/common/rounded_card.dart';

class SavedBloodBanksScreen extends StatelessWidget {
  const SavedBloodBanksScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final banks = context.watch<ProfileViewModel>().savedBloodBanks;

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(title: const Text('Saved Blood Banks')),
      body: banks.isEmpty
          ? const EmptyState(
              icon: Icons.bookmark_outline,
              title: 'No saved blood banks',
              subtitle: 'Save blood banks for quick access later.',
            )
          : ListView.builder(
              padding: const EdgeInsets.all(20),
              itemCount: banks.length,
              itemBuilder: (context, index) {
                final bank = banks[index];
                return RoundedCard(
                  onTap: () =>
                      context.push('${AppRoutes.bloodBankDetails}/${bank.id}'),
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
                          Icons.local_hospital_outlined,
                          color: AppColors.primary,
                        ),
                      ),
                      const SizedBox(width: 16),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              bank.name,
                              style: const TextStyle(
                                fontWeight: FontWeight.w600,
                                color: AppColors.accent,
                              ),
                            ),
                            Text(
                              bank.address,
                              style: const TextStyle(
                                fontSize: 12,
                                color: AppColors.accentLight,
                              ),
                            ),
                          ],
                        ),
                      ),
                      const Icon(Icons.chevron_right,
                          color: AppColors.accentLight),
                    ],
                  ),
                );
              },
            ),
    );
  }
}
