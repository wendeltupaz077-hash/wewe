import 'package:flutter/foundation.dart';
import '../core/services/notification_api_service.dart';
import '../core/services/storage_service.dart';
import '../data/models/notification_model.dart';

class NotificationsViewModel extends ChangeNotifier {
  NotificationsViewModel({
    StorageService? storageService,
    NotificationApiService? notificationApiService,
  })  : _storageService = storageService ?? StorageService(),
        _notificationApiService =
            notificationApiService ?? NotificationApiService();

  final StorageService _storageService;
  final NotificationApiService _notificationApiService;

  bool _isLoading = true;
  List<NotificationModel> _notifications = [];

  bool get isLoading => _isLoading;
  List<NotificationModel> get notifications => _notifications;
  int get unreadCount => _notifications.where((n) => !n.isRead).length;

  Future<void> loadData() async {
    _isLoading = true;
    notifyListeners();

    final token = await _storageService.getToken();

    if (token == null || token.isEmpty) {
      _notifications = [];
      _isLoading = false;
      notifyListeners();
      return;
    }

    try {
      final rawData = await _notificationApiService.fetchNotifications(token);
      _notifications = rawData
          .map((item) => NotificationModel.fromJson(item))
          .toList();
    } catch (_) {
      _notifications = [];
    }

    _isLoading = false;
    notifyListeners();
  }

  Future<void> markAsRead(String id) async {
    final token = await _storageService.getToken();
    if (token != null && token.isNotEmpty) {
      await _notificationApiService.markAsRead(token, id);
    }

    final index = _notifications.indexWhere((n) => n.id == id);
    if (index != -1) {
      _notifications[index] = NotificationModel(
        id: _notifications[index].id,
        title: _notifications[index].title,
        message: _notifications[index].message,
        type: _notifications[index].type,
        timestamp: _notifications[index].timestamp,
        isRead: true,
      );
      notifyListeners();
    }
  }

  Future<void> markAllAsRead() async {
    final token = await _storageService.getToken();
    if (token != null && token.isNotEmpty) {
      await _notificationApiService.markAllAsRead(token);
    }

    _notifications = _notifications
        .map((n) => NotificationModel(
              id: n.id,
              title: n.title,
              message: n.message,
              type: n.type,
              timestamp: n.timestamp,
              isRead: true,
            ))
        .toList();
    notifyListeners();
  }
}
