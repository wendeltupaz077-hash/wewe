import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'config/app_theme.dart';
import 'config/constants.dart';
import 'config/router.dart';
import 'core/services/storage_service.dart';
import 'viewmodels/auth_viewmodel.dart';
import 'viewmodels/home_viewmodel.dart';
import 'viewmodels/map_viewmodel.dart';
import 'viewmodels/notifications_viewmodel.dart';
import 'viewmodels/profile_viewmodel.dart';
import 'viewmodels/requests_viewmodel.dart';

class BloodMapApp extends StatelessWidget {
  const BloodMapApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        Provider(create: (_) => StorageService()),
        ChangeNotifierProvider(
          create: (context) =>
              AuthViewModel(storage: context.read<StorageService>())..checkAuthStatus(),
        ),
        ChangeNotifierProxyProvider2<AuthViewModel, StorageService,
            ProfileViewModel>(
          create: (_) => ProfileViewModel(
            AuthViewModel(storage: StorageService()),
            StorageService(),
          ),
          update: (_, authVm, storage, vm) =>
              vm ?? ProfileViewModel(authVm, storage),
        ),
        ChangeNotifierProvider(
          create: (_) => RequestsViewModel()..loadData(),
        ),
        ChangeNotifierProvider(
          create: (_) => HomeViewModel()..loadData(),
        ),
        ChangeNotifierProvider(
          create: (_) => MapViewModel()..loadData(),
        ),
        ChangeNotifierProvider(
          create: (context) => NotificationsViewModel(
            storageService: context.read<StorageService>(),
          )..loadData(),
        ),
      ],
      child: MaterialApp.router(
        title: AppConstants.appName,
        debugShowCheckedModeBanner: false,
        theme: AppTheme.lightTheme,
        routerConfig: createRouter(),
      ),
    );
  }
}
