import 'api_service.dart';

class NotificationApiService {
  NotificationApiService({ApiService? apiService})
      : _apiService = apiService ?? ApiService();

  final ApiService _apiService;

  Future<List<Map<String, dynamic>>> fetchNotifications(String token) async {
    final response = await _apiService.get(
      '/notifications',
      token: token,
    );

    if (response is List) {
      return List<Map<String, dynamic>>.from(
        response.map((item) => Map<String, dynamic>.from(item as Map)),
      );
    }

    throw Exception('Invalid notifications response');
  }

  Future<void> markAsRead(String token, String notificationId) async {
    await _apiService.post(
      '/notifications/$notificationId/mark-read',
      token: token,
    );
  }

  Future<void> markAllAsRead(String token) async {
    await _apiService.post(
      '/notifications/mark-all-read',
      token: token,
    );
  }
}
