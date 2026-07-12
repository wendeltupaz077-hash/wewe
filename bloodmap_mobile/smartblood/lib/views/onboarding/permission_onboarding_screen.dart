import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:permission_handler/permission_handler.dart';
import '../../config/app_colors.dart';
import '../../config/app_routes.dart';
import '../../core/services/storage_service.dart';
import '../../widgets/common/app_logo.dart';
import '../../widgets/common/page_indicator.dart';
import '../../widgets/common/primary_button.dart';
import '../../widgets/common/text_link_button.dart';

class PermissionOnboardingScreen extends StatefulWidget {
  const PermissionOnboardingScreen({super.key});

  @override
  State<PermissionOnboardingScreen> createState() =>
      _PermissionOnboardingScreenState();
}

class _PermissionOnboardingScreenState extends State<PermissionOnboardingScreen> {
  final PageController _pageController = PageController();
  int _currentPage = 0;
  final _storage = StorageService();

  static const _pages = [
    PermissionPageData(
      icon: Icons.notifications_active_outlined,
      title: 'Enable Notifications',
      description:
          'Get real-time alerts and updates about your requests and blood availability.',
      permission: Permission.notification,
    ),
    PermissionPageData(
      icon: Icons.location_on_outlined,
      title: 'Allow Location Services',
      description:
          'Location services make address searches more precise, helping you save time and speed up the process.',
      permission: Permission.location,
    ),
    PermissionPageData(
      icon: Icons.phone_android_outlined,
      title: 'Allow Access to Your Phone',
      description:
          'This is required to make and manage phone calls, and ensure your data is secure and protected.',
      permission: Permission.phone,
    ),
  ];

  bool get _isLastPage => _currentPage == _pages.length - 1;

  Future<void> _handlePermission(Permission permission) async {
    await permission.request();
  }

  Future<void> _nextPage() async {
    if (!_isLastPage) {
      await _handlePermission(_pages[_currentPage].permission);
      if (mounted) {
        await _pageController.nextPage(
          duration: const Duration(milliseconds: 450),
          curve: Curves.easeInOutCubic,
        );
      }
    } else {
      await _handlePermission(_pages[_currentPage].permission);
      await _storage.setPermissionOnboardingCompleted();
      if (mounted) {
        context.go(AppRoutes.login);
      }
    }
  }

  Future<void> _skip() async {
    await _storage.setPermissionOnboardingCompleted();
    if (mounted) {
      context.go(AppRoutes.login);
    }
  }

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.surface,
      body: SafeArea(
        child: Column(
          children: [
            Padding(
              padding: const EdgeInsets.fromLTRB(24, 20, 24, 8),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const AppLogo(size: 40, showText: false),
                  if (!_isLastPage)
                    TextLinkButton(
                      label: 'Skip',
                      onPressed: _skip,
                    )
                  else
                    const SizedBox(width: 48),
                ],
              ),
            ),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24),
              child: Text(
                'Page ${_currentPage + 1} of ${_pages.length}',
                style: TextStyle(
                  fontSize: 13,
                  fontWeight: FontWeight.w600,
                  color: AppColors.accentLight.withValues(alpha: 0.85),
                  letterSpacing: 0.2,
                ),
              ),
            ),
            Expanded(
              child: PageView.builder(
                controller: _pageController,
                itemCount: _pages.length,
                onPageChanged: (index) {
                  setState(() => _currentPage = index);
                },
                itemBuilder: (context, index) {
                  return AnimatedSwitcher(
                    duration: const Duration(milliseconds: 350),
                    switchInCurve: Curves.easeOutCubic,
                    child: _PermissionPage(
                      key: ValueKey(index),
                      data: _pages[index],
                    ),
                  );
                },
              ),
            ),
            Padding(
              padding: const EdgeInsets.fromLTRB(24, 0, 24, 16),
              child: PageIndicator(
                count: _pages.length,
                currentIndex: _currentPage,
              ),
            ),
            Padding(
              padding: const EdgeInsets.fromLTRB(24, 0, 24, 40),
              child: PrimaryButton(
                label: _isLastPage ? 'Get Started' : 'Next',
                onPressed: _nextPage,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _PermissionPage extends StatelessWidget {
  const _PermissionPage({super.key, required this.data});

  final PermissionPageData data;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 32),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Container(
            width: 240,
            height: 240,
            decoration: BoxDecoration(
              gradient: LinearGradient(
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
                colors: [
                  AppColors.primary.withValues(alpha: 0.12),
                  AppColors.primary.withValues(alpha: 0.04),
                ],
              ),
              shape: BoxShape.circle,
            ),
            child: Icon(
              data.icon,
              size: 100,
              color: AppColors.primary,
            ),
          ),
          const SizedBox(height: 48),
          Text(
            data.title,
            textAlign: TextAlign.center,
            style: const TextStyle(
              fontSize: 28,
              fontWeight: FontWeight.w800,
              color: AppColors.accent,
              height: 1.25,
              letterSpacing: -0.5,
            ),
          ),
          const SizedBox(height: 20),
          Text(
            data.description,
            textAlign: TextAlign.center,
            style: TextStyle(
              fontSize: 16,
              color: AppColors.accentLight.withValues(alpha: 0.9),
              height: 1.6,
            ),
          ),
        ],
      ),
    );
  }
}

class PermissionPageData {
  const PermissionPageData({
    required this.icon,
    required this.title,
    required this.description,
    required this.permission,
  });

  final IconData icon;
  final String title;
  final String description;
  final Permission permission;
}
