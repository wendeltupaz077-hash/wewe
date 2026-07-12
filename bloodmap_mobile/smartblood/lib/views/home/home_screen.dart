import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../config/app_colors.dart';
import '../../config/app_routes.dart';
import '../../config/constants.dart';
import '../../viewmodels/auth_viewmodel.dart';
import '../../viewmodels/home_viewmodel.dart';
import '../../widgets/common/blood_type_chip.dart';
import '../../widgets/common/search_bar_widget.dart';
import '../../widgets/common/shimmer_loading.dart';
import '../../widgets/home/home_widgets.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<HomeViewModel>();
    final user = context.watch<AuthViewModel>().currentUser;

    return Scaffold(
      backgroundColor: AppColors.background,
      body: SafeArea(
        child: vm.isLoading
            ? const ShimmerList()
            : RefreshIndicator(
                color: AppColors.primary,
                onRefresh: vm.refresh,
                child: CustomScrollView(
                  physics: const AlwaysScrollableScrollPhysics(),
                  slivers: [
                    SliverToBoxAdapter(
                      child: Padding(
                        padding: const EdgeInsets.fromLTRB(20, 16, 20, 0),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                const Text(
                                  AppConstants.appName,
                                  style: TextStyle(
                                    fontSize: 20,
                                    fontWeight: FontWeight.bold,
                                    color: AppColors.accent,
                                  ),
                                ),
                                IconButton(
                                  onPressed: () {},
                                  icon: Badge(
                                    label: const Text('2'),
                                    child: const Icon(Icons.notifications_outlined),
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 20),
                            WelcomeBanner(
                              userName: user?.firstName ?? 'User',
                            ),
                            const SizedBox(height: 24),
                            const SearchBarWidget(
                              hint: 'Search blood type...',
                              readOnly: true,
                            ),
                            const SizedBox(height: 16),
                            SingleChildScrollView(
                              scrollDirection: Axis.horizontal,
                              child: Row(
                                children: AppConstants.bloodTypes.map((type) {
                                  return Padding(
                                    padding: const EdgeInsets.only(right: 8),
                                    child: BloodTypeChip(
                                      bloodType: type,
                                      isSelected:
                                          vm.selectedBloodType == type,
                                      compact: true,
                                      onTap: () => vm.selectBloodType(type),
                                    ),
                                  );
                                }).toList(),
                              ),
                            ),
                            const SizedBox(height: 24),
                            _SectionHeader(
                              title: 'Nearby Blood Banks',
                              action: 'See all',
                              onAction: () {},
                            ),
                            const SizedBox(height: 12),
                          ],
                        ),
                      ),
                    ),
                    SliverToBoxAdapter(
                      child: SizedBox(
                        height: 180,
                        child: ListView.builder(
                          scrollDirection: Axis.horizontal,
                          padding: const EdgeInsets.symmetric(horizontal: 20),
                          itemCount: vm.filteredBloodBanks.length,
                          itemBuilder: (context, index) {
                            final bank = vm.filteredBloodBanks[index];
                            return NearbyBloodBankCard(
                              bloodBank: bank,
                              onTap: () => context.push(
                                '${AppRoutes.bloodBankDetails}/${bank.id}',
                              ),
                            );
                          },
                        ),
                      ),
                    ),
                    SliverToBoxAdapter(
                      child: Padding(
                        padding: const EdgeInsets.fromLTRB(20, 24, 20, 0),
                        child: _SectionHeader(
                          title: 'Blood Availability',
                          action: 'Filter',
                          onAction: () {},
                        ),
                      ),
                    ),
                    SliverPadding(
                      padding: const EdgeInsets.fromLTRB(20, 12, 20, 0),
                      sliver: SliverList(
                        delegate: SliverChildBuilderDelegate(
                          (context, index) {
                            final bank = vm.filteredBloodBanks[index];
                            return BloodAvailabilityCard(
                              bloodBank: bank,
                              onTap: () => context.push(
                                '${AppRoutes.bloodBankDetails}/${bank.id}',
                              ),
                            );
                          },
                          childCount: vm.filteredBloodBanks.length,
                        ),
                      ),
                    ),
                    SliverToBoxAdapter(
                      child: Padding(
                        padding: const EdgeInsets.fromLTRB(20, 24, 20, 0),
                        child: _SectionHeader(
                          title: 'Latest Announcements',
                          action: 'More',
                          onAction: () {},
                        ),
                      ),
                    ),
                    SliverPadding(
                      padding: const EdgeInsets.fromLTRB(20, 12, 20, 100),
                      sliver: SliverList(
                        delegate: SliverChildBuilderDelegate(
                          (context, index) => AnnouncementCard(
                            announcement: vm.announcements[index],
                          ),
                          childCount: vm.announcements.length,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => context.push(AppRoutes.emergencyRequest),
        backgroundColor: AppColors.primary,
        foregroundColor: Colors.white,
        elevation: 4,
        icon: const Icon(Icons.emergency),
        label: const Text(
          'Emergency Request',
          style: TextStyle(fontWeight: FontWeight.w600),
        ),
      ),
    );
  }
}

class _SectionHeader extends StatelessWidget {
  const _SectionHeader({
    required this.title,
    required this.action,
    required this.onAction,
  });

  final String title;
  final String action;
  final VoidCallback onAction;

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(
          title,
          style: const TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.bold,
            color: AppColors.accent,
          ),
        ),
        TextButton(
          onPressed: onAction,
          child: Text(action),
        ),
      ],
    );
  }
}
