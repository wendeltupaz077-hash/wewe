import 'package:flutter/foundation.dart';
import '../core/services/storage_service.dart';
import '../core/services/auth_api_service.dart';
import '../data/models/user_model.dart';
import '../widgets/common/auth_method_selector.dart';

class AuthViewModel extends ChangeNotifier {
  AuthViewModel({StorageService? storage, AuthApiService? apiService})
      : _storage = storage ?? StorageService(),
        _apiService = apiService ?? AuthApiService();

  final StorageService _storage;
  final AuthApiService _apiService;

  bool _isLoading = false;
  bool _isSendingOtp = false;
  bool _isAuthenticated = false;
  String? _error;
  String? _pendingPhone;
  String? _pendingEmail;
  AuthContactMethod? _verificationMethod;
  UserModel? _currentUser;
  String? _otpStatusMessage;
  String? _pendingPassword;
  String? _pendingFirstName;
  String? _pendingLastName;
  String? _pendingMiddleName;

  bool get isLoading => _isLoading;
  bool get isSendingOtp => _isSendingOtp;
  bool get isAuthenticated => _isAuthenticated;
  String? get error => _error;
  String? get pendingPhone => _pendingPhone;
  String? get pendingEmail => _pendingEmail;
  AuthContactMethod? get verificationMethod => _verificationMethod;
  UserModel? get currentUser => _currentUser;
  String? get otpStatusMessage => _otpStatusMessage;

  static bool isEmailCredential(String value) {
    return value.trim().contains('@');
  }

  Future<void> checkAuthStatus() async {
    _isAuthenticated = await _storage.isLoggedIn();
    _currentUser = await _storage.getUser();
    notifyListeners();
  }

  void setUser(UserModel user) {
    _currentUser = user;
    notifyListeners();
  }

  Future<bool> login(String credential, String password) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    final trimmed = credential.trim();
    if (trimmed.isEmpty || password.length < 6) {
      _error = isEmailCredential(trimmed)
          ? 'Invalid email address or password'
          : 'Invalid phone number or password';
      _isLoading = false;
      notifyListeners();
      return false;
    }

    if (isEmailCredential(trimmed)) {
      _pendingEmail = trimmed;
      _pendingPhone = null;
      _verificationMethod = AuthContactMethod.email;
    } else {
      // Normalize phone number to 63 prefix
      final digits = trimmed.replaceAll(RegExp(r'\D'), '');
      _pendingPhone = '63$digits';
      _pendingEmail = null;
      _verificationMethod = AuthContactMethod.phone;
    }
    _pendingPassword = password;

    notifyListeners();
    final success = await sendOtp();
    _isLoading = false;
    notifyListeners();
    return success;
  }

  Future<bool> register({
    required String firstName,
    required String lastName,
    String? middleName,
    String? email,
    String? phone,
    required String password,
    required AuthContactMethod method,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    if (method == AuthContactMethod.email) {
      final normalizedEmail = email?.trim() ?? '';
      _pendingEmail = normalizedEmail;
      _pendingPhone = null;
      _verificationMethod = AuthContactMethod.email;
    } else {
      // Normalize phone number to 63 prefix
      final digits = phone?.replaceAll(RegExp(r'\D'), '') ?? '';
      _pendingPhone = '63$digits';
      _pendingEmail = null;
      _verificationMethod = AuthContactMethod.phone;
    }
    _pendingPassword = password;
    _pendingFirstName = firstName;
    _pendingLastName = lastName;
    _pendingMiddleName = middleName;

    notifyListeners();
    final success = await sendOtp();
    _isLoading = false;
    notifyListeners();
    return success;
  }

  Future<bool> sendOtp() async {
    _isSendingOtp = true;
    _otpStatusMessage = _verificationMethod == AuthContactMethod.email
        ? 'Sending verification code...'
        : 'Sending OTP...';
    notifyListeners();

    try {
      final identifier = _verificationMethod == AuthContactMethod.email
          ? _pendingEmail!
          : _pendingPhone!;
      final channel = _verificationMethod == AuthContactMethod.email
          ? 'email'
          : 'phone';

      await _apiService.sendOtp(
        identifier: identifier,
        channel: channel,
      );

      _isSendingOtp = false;
      _otpStatusMessage = _verificationMethod == AuthContactMethod.email
          ? 'Verification code sent!'
          : 'OTP sent!';
      notifyListeners();
      return true;
    } catch (e) {
      _isSendingOtp = false;
      _otpStatusMessage = null;
      _error = e.toString();
      notifyListeners();
      return false;
    }
  }

  Future<bool> verifyOtp(String otp) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final identifier = _verificationMethod == AuthContactMethod.email
          ? _pendingEmail!
          : _pendingPhone!;

      final session = await _apiService.verifyOtp(
        identifier: identifier,
        code: otp,
        password: _pendingPassword,
        firstName: _pendingFirstName,
        lastName: _pendingLastName,
        middleName: _pendingMiddleName,
      );

      await _storage.setLoggedIn(true);
      await _storage.setToken(session.token);
      await _storage.setUser(session.user);
      
      _isAuthenticated = true;
      _currentUser = session.user;
      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _error = e.toString();
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<bool> resetPassword(String credential) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    final trimmed = credential.trim();
    if (trimmed.isEmpty) {
      _error = 'Email or phone number is required';
      _isLoading = false;
      notifyListeners();
      return false;
    }

    if (isEmailCredential(trimmed)) {
      _pendingEmail = trimmed;
      _pendingPhone = null;
      _verificationMethod = AuthContactMethod.email;
    } else {
      // Normalize phone number to 63 prefix
      final digits = trimmed.replaceAll(RegExp(r'\D'), '');
      _pendingPhone = '63$digits';
      _pendingEmail = null;
      _verificationMethod = AuthContactMethod.phone;
    }

    _isLoading = false;
    notifyListeners();
    return true;
  }

  Future<void> logout() async {
    await _storage.logout();
    await _storage.setToken(null);
    await _storage.setUser(null);
    _isAuthenticated = false;
    _currentUser = null;
    notifyListeners();
  }

  void clearError() {
    _error = null;
    notifyListeners();
  }
}
