import 'package:flutter/material.dart';
import '../../config/app_colors.dart';
import 'app_logo.dart';

class AuthHeader extends StatelessWidget {
  const AuthHeader({
    super.key,
    this.showBack = false,
    this.onBack,
  });

  final bool showBack;
  final VoidCallback? onBack;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 8),
      child: Row(
        children: [
          if (showBack)
            IconButton(
              onPressed: onBack ?? () => Navigator.of(context).pop(),
              icon: const Icon(Icons.arrow_back_ios_new, size: 20),
              color: AppColors.accent,
            )
          else
            const SizedBox(width: 48),
          Expanded(
            child: Center(
              child: FittedBox(
                fit: BoxFit.scaleDown,
                child: const AppLogo(size: 40, showText: false),
              ),
            ),
          ),
          const SizedBox(width: 48),
        ],
      ),
    );
  }
}
