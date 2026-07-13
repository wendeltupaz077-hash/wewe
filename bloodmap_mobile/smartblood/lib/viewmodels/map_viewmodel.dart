import 'package:flutter/foundation.dart';
import '../data/mock/mock_data.dart';
import '../data/models/announcement_model.dart';
import '../data/models/blood_bank_model.dart';

class MapViewModel extends ChangeNotifier {
  bool _showDonors = true;
  bool _showBloodBanks = true;
  bool _isLoading = true;

  bool get showDonors => _showDonors;
  bool get showBloodBanks => _showBloodBanks;
  bool get isLoading => _isLoading;

  List<BloodBankModel> get bloodBanks => MockData.bloodBanks;
  List<DonorModel> get donors => MockData.donors;

  Future<void> loadData() async {
    _isLoading = true;
    notifyListeners();
    await Future.delayed(const Duration(milliseconds: 500));
    _isLoading = false;
    notifyListeners();
  }

  void toggleDonors(bool value) {
    _showDonors = value;
    notifyListeners();
  }

  void toggleBloodBanks(bool value) {
    _showBloodBanks = value;
    notifyListeners();
  }
}
