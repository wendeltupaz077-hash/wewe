class AppConstants {
  AppConstants._();

  static const String appName = 'Blood Map PH';
  static const String appTagline = 'Saving Lives Through Smart Blood Management';
  static const int splashDurationSeconds = 2;
  static const int otpLength = 6;
  static const int otpResendSeconds = 60;

  /// Android emulator: 10.0.2.2 maps to host machine localhost.
  /// Override with: flutter run --dart-define=API_BASE_URL=http://YOUR_IP:8000/api/v1
  static const String apiBaseUrl = String.fromEnvironment(
    'API_BASE_URL',
    defaultValue: 'http://localhost:8000/api/v1',
  );

  static const List<String> bloodTypes = [
    'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-',
  ];

  static const List<String> requestStatuses = [
    'Pending',
    'Processing',
    'Approved',
    'Fulfilled',
    'Cancelled',
  ];
}
