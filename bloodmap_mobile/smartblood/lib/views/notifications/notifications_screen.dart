import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../../config/app_colors.dart';
import '../../data/models/notification_model.dart';
import '../../viewmodels/notifications_viewmodel.dart';
import '../../widgets/common/empty_state.dart';
import '../../widgets/common/rounded_card.dart';
import '../../widgets/common/shimmer_loading.dart';

class NotificationsScreen extends StatelessWidget {
  const NotificationsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<NotificationsViewModel>();

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Notifications'),
        actions: [
          if (vm.unreadCount > 0)
            TextButton(
              onPressed: vm.markAllAsRead,
              child: const Text('Mark all read'),
            ),
        ],
      ),
      body: vm.isLoading
          ? const ShimmerList()
          : vm.notifications.isEmpty
              ? const EmptyState(
                  icon: Icons.notifications_none,
                  title: 'No notifications',
                  subtitle: 'You\'re all caught up! New alerts will appear here.',
                )
              : RefreshIndicator(
                  color: AppColors.primary,
                  onRefresh: vm.loadData,
                  child: ListView.builder(
                    padding: const EdgeInsets.all(20),
                    itemCount: vm.notifications.length,
                    itemBuilder: (context, index) {
                      final notification = vm.notifications[index];
                      return _NotificationTile(
                        notification: notification,
                        onTap: () => vm.markAsRead(notification.id),
                      );
                    },
                  ),
                ),
    );
  }
}

class _NotificationTile extends StatelessWidget {
  const _NotificationTile({
    required this.notification,
    required this.onTap,
  });

  final NotificationModel notification;
  final VoidCallback onTap;

  IconData get _icon {
    switch (notification.type) {
      case NotificationType.emergency:
        return Icons.emergency;
      case NotificationType.donation:
        return Icons.volunteer_activism;
      case NotificationType.availability:
        return Icons.bloodtype;
      case NotificationType.system:
        return Icons.info_outline;
    }
  }

  Color get _color {
    switch (notification.type) {
      case NotificationType.emergency:
        return AppColors.primary;
      case NotificationType.donation:
        return AppColors.success;
      case NotificationType.availability:
        return const Color(0xFF0077B6);
      case NotificationType.system:
        return AppColors.accentLight;
    }
  }

  String get _typeLabel {
    switch (notification.type) {
      case NotificationType.emergency:
        return 'Emergency Alert';
      case NotificationType.donation:
        return 'Donation Request';
      case NotificationType.availability:
        return 'Availability Update';
      case NotificationType.system:
        return 'System';
    }
  }

  @override
  Widget build(BuildContext context) {
    final timeFormat = DateFormat('h:mm a');
    final dateFormat = DateFormat('MMM d');

    return RoundedCard(
      onTap: onTap,
      margin: const EdgeInsets.only(bottom: 12),
      color: notification.isRead
          ? AppColors.surface
          : AppColors.primary.withValues(alpha: 0.04),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: _color.withValues(alpha: 0.12),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Icon(_icon, color: _color, size: 22),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Text(
                      _typeLabel,
                      style: TextStyle(
                        fontSize: 11,
                        fontWeight: FontWeight.w600,
                        color: _color,
                      ),
                    ),
                    const Spacer(),
                    Text(
                      '${dateFormat.format(notification.timestamp)} • ${timeFormat.format(notification.timestamp)}',
                      style: const TextStyle(
                        fontSize: 11,
                        color: AppColors.accentLight,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 6),
                Text(
                  notification.title,
                  style: TextStyle(
                    fontSize: 15,
                    fontWeight: notification.isRead
                        ? FontWeight.w500
                        : FontWeight.w600,
                    color: AppColors.accent,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  notification.message,
                  style: const TextStyle(
                    fontSize: 13,
                    color: AppColors.accentLight,
                    height: 1.4,
                  ),
                ),
              ],
            ),
          ),
          if (!notification.isRead)
            Container(
              width: 8,
              height: 8,
              margin: const EdgeInsets.only(top: 4),
              decoration: const BoxDecoration(
                color: AppColors.primary,
                shape: BoxShape.circle,
              ),
            ),
        ],
      ),
    );
  }
}
