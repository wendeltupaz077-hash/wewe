import 'package:flutter/material.dart';
import '../../config/app_colors.dart';

class BloodTypeChip extends StatelessWidget {
  const BloodTypeChip({
    super.key,
    required this.bloodType,
    this.isSelected = false,
    this.onTap,
    this.compact = false,
  });

  final String bloodType;
  final bool isSelected;
  final VoidCallback? onTap;
  final bool compact;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        padding: EdgeInsets.symmetric(
          horizontal: compact ? 12 : 16,
          vertical: compact ? 6 : 10,
        ),
        decoration: BoxDecoration(
          color: isSelected
              ? AppColors.primary
              : AppColors.primary.withValues(alpha: 0.08),
          borderRadius: BorderRadius.circular(compact ? 20 : 12),
          border: Border.all(
            color: isSelected ? AppColors.primary : AppColors.border,
          ),
        ),
        child: Text(
          bloodType,
          style: TextStyle(
            color: isSelected ? Colors.white : AppColors.primary,
            fontWeight: FontWeight.w600,
            fontSize: compact ? 13 : 14,
          ),
        ),
      ),
    );
  }
}
