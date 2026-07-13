enum NotificationType { emergency, donation, availability, system }

class NotificationModel {
  const NotificationModel({
    required this.id,
    required this.title,
    required this.message,
    required this.type,
    required this.timestamp,
    this.isRead = false,
  });

  final String id;
  final String title;
  final String message;
  final NotificationType type;
  final DateTime timestamp;
  final bool isRead;

  factory NotificationModel.fromJson(Map<String, dynamic> json) {
    return NotificationModel(
      id: json['id'].toString(),
      title: json['title']?.toString() ?? '',
      message: json['message']?.toString() ?? '',
      type: _typeFromString(json['type']?.toString() ?? 'system'),
      timestamp: DateTime.tryParse(json['created_at']?.toString() ?? '') ??
          DateTime.now(),
      isRead: json['is_read'] == true || json['is_read']?.toString() == '1',
    );
  }

  static NotificationType _typeFromString(String value) {
    switch (value.toLowerCase()) {
      case 'emergency':
      case 'urgent':
        return NotificationType.emergency;
      case 'donation':
      case 'request':
        return NotificationType.donation;
      case 'availability':
      case 'stock':
      case 'inventory':
        return NotificationType.availability;
      default:
        return NotificationType.system;
    }
  }
}
