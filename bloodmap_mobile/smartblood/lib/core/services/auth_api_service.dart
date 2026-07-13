import '../../data/models/user_model.dart';
import 'api_service.dart';

class AuthApiService {
  AuthApiService({ApiService? apiService})
      : _apiService = apiService ?? ApiService();

  final ApiService _apiService;

  Future<void> sendOtp({
    required String identifier,
    required String channel,
  }) async {
    await _apiService.post(
      '/auth/otp/send',
      body: {
        'identifier': identifier,
        'channel': channel,
      },
    );
  }

  Future<AuthSession> verifyOtp({
    required String identifier,
    required String code,
    String? password,
    String? firstName,
    String? lastName,
    String? middleName,
  }) async {
    final data = await _apiService.post(
      '/auth/otp/verify',
      body: {
        'identifier': identifier,
        'code': code,
        ...?password == null ? null : {'password': password},
        ...?firstName == null ? null : {'first_name': firstName},
        ...?lastName == null ? null : {'last_name': lastName},
        ...?middleName == null ? null : {'middle_name': middleName},
      },
    );

    return AuthSession(
      token: data['token'] as String,
      user: UserModel.fromJson(data['user'] as Map<String, dynamic>),
    );
  }
}

class AuthSession {
  const AuthSession({required this.token, required this.user});

  final String token;
  final UserModel user;
}
