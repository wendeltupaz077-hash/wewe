import 'package:flutter/foundation.dart';
import '../core/services/storage_service.dart';
import '../data/mock/mock_data.dart';
import '../data/models/announcement_model.dart';
import '../data/models/blood_bank_model.dart';
import '../data/models/user_model.dart';
import 'auth_viewmodel.dart';

class ProfileViewModel extends ChangeNotifier {
  final AuthViewModel _authViewModel;
  final StorageService _storageService;
  final List<DonationRecord> _donationHistory = MockData.donationHistory;
  final List<BloodBankModel> _savedBloodBanks = [MockData.bloodBanks.first];

  ProfileViewModel(this._authViewModel, this._storageService) {
    _authViewModel.addListener(_onAuthViewModelChanged);
  }

  void _onAuthViewModelChanged() {
    notifyListeners();
  }

  @override
  void dispose() {
    _authViewModel.removeListener(_onAuthViewModelChanged);
    super.dispose();
  }

  UserModel get user => _authViewModel.currentUser ?? MockData.user;
  List<DonationRecord> get donationHistory => _donationHistory;
  List<BloodBankModel> get savedBloodBanks => _savedBloodBanks;

  Future<void> updateProfile({
    String? firstName,
    String? lastName,
    String? middleName,
    String? email,
    String? phone,
    String? bloodType,
    String? address,
    String? avatarUrl,
  }) async {
    final updatedUser = user.copyWith(
      firstName: firstName,
      lastName: lastName,
      middleName: middleName,
      email: email,
      phone: phone,
      bloodType: bloodType,
      address: address,
      avatarUrl: avatarUrl,
    );
    _authViewModel.setUser(updatedUser);
    await _storageService.setUser(updatedUser);
    notifyListeners();
  }
}
