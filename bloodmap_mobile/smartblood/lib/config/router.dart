import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../views/auth/forgot_password_screen.dart';
import '../views/auth/login_screen.dart';
import '../views/auth/otp_verification_screen.dart';
import '../views/auth/register_screen.dart';
import '../views/main/main_shell.dart';
import '../views/onboarding/permission_onboarding_screen.dart';
import '../views/profile/donation_history_screen.dart';
import '../views/profile/edit_profile_screen.dart';
import '../views/profile/request_history_screen.dart';
import '../views/profile/saved_blood_banks_screen.dart';
import '../views/requests/request_details_screen.dart';
import '../views/settings/about_screen.dart';
import '../views/settings/help_center_screen.dart';
import '../views/settings/privacy_policy_screen.dart';
import '../views/settings/settings_screen.dart';
import '../views/splash/splash_screen.dart';
import '../views/blood_bank/blood_bank_details_screen.dart';
import '../views/donor/donor_details_screen.dart';
import '../views/emergency/emergency_request_screen.dart';
import '../views/appointment/appointment_booking_screen.dart';
import '../views/appointment/donation_schedule_screen.dart';
import '../views/qr/qr_verification_screen.dart';
import 'app_routes.dart';

final rootNavigatorKey = GlobalKey<NavigatorState>();
final shellNavigatorKey = GlobalKey<NavigatorState>();

GoRouter createRouter() {
  return GoRouter(
    navigatorKey: rootNavigatorKey,
    initialLocation: AppRoutes.splash,
    routes: [
      GoRoute(
        path: AppRoutes.splash,
        pageBuilder: (context, state) => CustomTransitionPage(
          key: state.pageKey,
          child: const SplashScreen(),
          transitionsBuilder: (_, animation, _, child) =>
              FadeTransition(opacity: animation, child: child),
        ),
      ),
      GoRoute(
        path: AppRoutes.permissionOnboarding,
        pageBuilder: (context, state) => CustomTransitionPage(
          key: state.pageKey,
          child: const PermissionOnboardingScreen(),
          transitionsBuilder: (_, animation, _, child) {
            return SlideTransition(
              position: Tween<Offset>(
                begin: const Offset(0, 0.1),
                end: Offset.zero,
              ).animate(CurvedAnimation(
                parent: animation,
                curve: Curves.easeOutCubic,
              )),
              child: FadeTransition(opacity: animation, child: child),
            );
          },
        ),
      ),
      GoRoute(
        path: AppRoutes.login,
        pageBuilder: (context, state) => _slidePage(state, const LoginScreen()),
      ),
      GoRoute(
        path: AppRoutes.register,
        pageBuilder: (context, state) =>
            _slidePage(state, const RegisterScreen()),
      ),
      GoRoute(
        path: AppRoutes.forgotPassword,
        pageBuilder: (context, state) =>
            _slidePage(state, const ForgotPasswordScreen()),
      ),
      GoRoute(
        path: AppRoutes.otpVerification,
        pageBuilder: (context, state) =>
            _slidePage(state, const OtpVerificationScreen()),
      ),
      GoRoute(
        path: AppRoutes.main,
        pageBuilder: (context, state) => CustomTransitionPage(
          key: state.pageKey,
          child: const MainShell(),
          transitionsBuilder: (_, animation, _, child) =>
              FadeTransition(opacity: animation, child: child),
        ),
      ),
      GoRoute(
        path: AppRoutes.emergencyRequest,
        pageBuilder: (context, state) =>
            _slidePage(state, const EmergencyRequestScreen()),
      ),
      GoRoute(
        path: '${AppRoutes.bloodBankDetails}/:id',
        pageBuilder: (context, state) => _slidePage(
          state,
          BloodBankDetailsScreen(id: state.pathParameters['id']!),
        ),
      ),
      GoRoute(
        path: '${AppRoutes.donorDetails}/:id',
        pageBuilder: (context, state) => _slidePage(
          state,
          DonorDetailsScreen(id: state.pathParameters['id']!),
        ),
      ),
      GoRoute(
        path: '${AppRoutes.requestDetails}/:id',
        pageBuilder: (context, state) => _slidePage(
          state,
          RequestDetailsScreen(id: state.pathParameters['id']!),
        ),
      ),
      GoRoute(
        path: AppRoutes.appointmentBooking,
        pageBuilder: (context, state) =>
            _slidePage(state, const AppointmentBookingScreen()),
      ),
      GoRoute(
        path: AppRoutes.donationSchedule,
        pageBuilder: (context, state) =>
            _slidePage(state, const DonationScheduleScreen()),
      ),
      GoRoute(
        path: AppRoutes.qrVerification,
        pageBuilder: (context, state) =>
            _slidePage(state, const QrVerificationScreen()),
      ),
      GoRoute(
        path: AppRoutes.editProfile,
        pageBuilder: (context, state) =>
            _slidePage(state, const EditProfileScreen()),
      ),
      GoRoute(
        path: AppRoutes.donationHistory,
        pageBuilder: (context, state) =>
            _slidePage(state, const DonationHistoryScreen()),
      ),
      GoRoute(
        path: AppRoutes.requestHistory,
        pageBuilder: (context, state) =>
            _slidePage(state, const RequestHistoryScreen()),
      ),
      GoRoute(
        path: AppRoutes.savedBloodBanks,
        pageBuilder: (context, state) =>
            _slidePage(state, const SavedBloodBanksScreen()),
      ),
      GoRoute(
        path: AppRoutes.settings,
        pageBuilder: (context, state) =>
            _slidePage(state, const SettingsScreen()),
      ),
      GoRoute(
        path: AppRoutes.about,
        pageBuilder: (context, state) => _slidePage(state, const AboutScreen()),
      ),
      GoRoute(
        path: AppRoutes.helpCenter,
        pageBuilder: (context, state) =>
            _slidePage(state, const HelpCenterScreen()),
      ),
      GoRoute(
        path: AppRoutes.privacyPolicy,
        pageBuilder: (context, state) =>
            _slidePage(state, const PrivacyPolicyScreen()),
      ),
    ],
  );
}

CustomTransitionPage _slidePage(GoRouterState state, Widget child) {
  return CustomTransitionPage(
    key: state.pageKey,
    child: child,
    transitionsBuilder: (_, animation, _, child) {
      return SlideTransition(
        position: Tween<Offset>(
          begin: const Offset(1, 0),
          end: Offset.zero,
        ).animate(CurvedAnimation(
          parent: animation,
          curve: Curves.easeOutCubic,
        )),
        child: child,
      );
    },
  );
}
