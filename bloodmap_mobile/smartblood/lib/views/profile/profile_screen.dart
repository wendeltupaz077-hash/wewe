import 'dart:io';
import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../config/app_colors.dart';
import '../../config/app_routes.dart';
import '../../viewmodels/auth_viewmodel.dart';
import '../../viewmodels/profile_viewmodel.dart';
import '../../widgets/common/rounded_card.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final profileVm = context.watch<ProfileViewModel>();
    final user = profileVm.user;

    return Scaffold(
      backgroundColor: AppColors.background,
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.fromLTRB(20, 16, 20, 32),
          child: Column(
            children: [
              const Text(
                'Profile',
                style: TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: AppColors.accent,
                ),
              ),
              const SizedBox(height: 24),
              RoundedCard(
                child: Column(
                  children: [
                  GestureDetector(
                    onTap: () => context.push(AppRoutes.editProfile),
                    child: Stack(
                      children: [
                        CircleAvatar(
                          radius: 40,
                          backgroundColor:
                              AppColors.primary.withValues(alpha: 0.12),
                          backgroundImage: user.avatarUrl != null &&
                                  user.avatarUrl!.isNotEmpty
                              ? FileImage(File(user.avatarUrl!))
                              : null,
                          child: user.avatarUrl == null ||
                                  user.avatarUrl!.isEmpty
                              ? Text(
                                  user.firstName[0] + user.lastName[0],
                                  style: const TextStyle(
                                    fontSize: 28,
                                    fontWeight: FontWeight.bold,
                                    color: AppColors.primary,
                                  ),
                                )
                              : null,
                        ),
                        Positioned(
                          bottom: 0,
                          right: 0,
                          child: Container(
                            padding: const EdgeInsets.all(4),
                            decoration: BoxDecoration(
                              color: AppColors.primary,
                              shape: BoxShape.circle,
                            ),
                            child: const Icon(
                              Icons.edit,
                              color: Colors.white,
                              size: 16,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                    const SizedBox(height: 16),
                    Text(
                      user.fullName,
                      style: const TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                        color: AppColors.accent,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      user.email,
                      style: const TextStyle(
                        fontSize: 14,
                        color: AppColors.accentLight,
                      ),
                    ),
                    if (user.bloodType != null) ...[
                      const SizedBox(height: 12),
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
                          'Blood Type: ${user.bloodType}',
                          style: const TextStyle(
                            fontWeight: FontWeight.w600,
                            color: AppColors.primary,
                          ),
                        ),
                      ),
                    ],
                    const SizedBox(height: 16),
                    OutlinedButton.icon(
                      onPressed: () => context.push(AppRoutes.editProfile),
                      icon: const Icon(Icons.edit_outlined, size: 18),
                      label: const Text('Edit Profile'),
                      style: OutlinedButton.styleFrom(
                        foregroundColor: AppColors.primary,
                        side: const BorderSide(color: AppColors.primary),
                        shape: const StadiumBorder(),
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 24),
              _ProfileSection(
                title: 'Activity',
                items: [
                  _ProfileMenuItem(
                    icon: Icons.history,
                    title: 'Donation History',
                    subtitle: '${profileVm.donationHistory.length} donations',
                    onTap: () => context.push(AppRoutes.donationHistory),
                  ),
                  _ProfileMenuItem(
                    icon: Icons.assignment_outlined,
                    title: 'Request History',
                    subtitle: 'View past blood requests',
                    onTap: () => context.push(AppRoutes.requestHistory),
                  ),
                  _ProfileMenuItem(
                    icon: Icons.bookmark_outline,
                    title: 'Saved Blood Banks',
                    subtitle: '${profileVm.savedBloodBanks.length} saved',
                    onTap: () => context.push(AppRoutes.savedBloodBanks),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              _ProfileSection(
                title: 'Services',
                items: [
                  _ProfileMenuItem(
                    icon: Icons.calendar_month_outlined,
                    title: 'Donation Schedule',
                    onTap: () => context.push(AppRoutes.donationSchedule),
                  ),
                  _ProfileMenuItem(
                    icon: Icons.event_available_outlined,
                    title: 'Book Appointment',
                    onTap: () => context.push(AppRoutes.appointmentBooking),
                  ),
                  _ProfileMenuItem(
                    icon: Icons.qr_code_scanner,
                    title: 'QR Verification',
                    onTap: () => context.push(AppRoutes.qrVerification),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              _ProfileSection(
                title: 'Settings',
                items: [
                  _ProfileMenuItem(
                    icon: Icons.settings_outlined,
                    title: 'Settings',
                    onTap: () => context.push(AppRoutes.settings),
                  ),
                  _ProfileMenuItem(
                    icon: Icons.help_outline,
                    title: 'Help Center',
                    onTap: () => context.push(AppRoutes.helpCenter),
                  ),
                  _ProfileMenuItem(
                    icon: Icons.info_outline,
                    title: 'About',
                    onTap: () => context.push(AppRoutes.about),
                  ),
                ],
              ),
              const SizedBox(height: 24),
              SizedBox(
                width: double.infinity,
                child: OutlinedButton.icon(
                  onPressed: () async {
                    await context.read<AuthViewModel>().logout();
                    if (context.mounted) context.go(AppRoutes.login);
                  },
                  icon: const Icon(Icons.logout, color: AppColors.primary),
                  label: const Text(
                    'Logout',
                    style: TextStyle(color: AppColors.primary),
                  ),
                  style: OutlinedButton.styleFrom(
                    side: const BorderSide(color: AppColors.primary),
                    shape: const StadiumBorder(),
                    padding: const EdgeInsets.symmetric(vertical: 14),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _ProfileSection extends StatelessWidget {
  const _ProfileSection({required this.title, required this.items});

  final String title;
  final List<_ProfileMenuItem> items;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.only(left: 4, bottom: 8),
          child: Text(
            title,
            style: const TextStyle(
              fontSize: 14,
              fontWeight: FontWeight.w600,
              color: AppColors.accentLight,
            ),
          ),
        ),
        RoundedCard(
          padding: EdgeInsets.zero,
          child: Column(
            children: items,
          ),
        ),
      ],
    );
  }
}

class _ProfileMenuItem extends StatelessWidget {
  const _ProfileMenuItem({
    required this.icon,
    required this.title,
    this.subtitle,
    required this.onTap,
  });

  final IconData icon;
  final String title;
  final String? subtitle;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return ListTile(
      onTap: onTap,
      leading: Container(
        padding: const EdgeInsets.all(8),
        decoration: BoxDecoration(
          color: AppColors.primary.withValues(alpha: 0.08),
          borderRadius: BorderRadius.circular(10),
        ),
        child: Icon(icon, color: AppColors.primary, size: 22),
      ),
      title: Text(
        title,
        style: const TextStyle(
          fontWeight: FontWeight.w500,
          color: AppColors.accent,
        ),
      ),
      subtitle: subtitle != null
          ? Text(
              subtitle!,
              style: const TextStyle(fontSize: 12, color: AppColors.accentLight),
            )
          : null,
      trailing: const Icon(Icons.chevron_right, color: AppColors.accentLight),
    );
  }
}
