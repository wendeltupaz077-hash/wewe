import 'package:flutter/foundation.dart';
import '../data/mock/mock_data.dart';
import '../data/models/announcement_model.dart';
import '../data/models/blood_bank_model.dart';

class HomeViewModel extends ChangeNotifier {
  bool _isLoading = true;
  String? _selectedBloodType;
  List<BloodBankModel> _bloodBanks = [];
  List<AnnouncementModel> _announcements = [];

  bool get isLoading => _isLoading;
  String? get selectedBloodType => _selectedBloodType;
  List<BloodBankModel> get bloodBanks => _bloodBanks;
  List<AnnouncementModel> get announcements => _announcements;

  Future<void> loadData() async {
    _isLoading = true;
    notifyListeners();

    await Future.delayed(const Duration(milliseconds: 800));

    _bloodBanks = MockData.bloodBanks;
    _announcements = MockData.announcements;
    _isLoading = false;
    notifyListeners();
  }

  Future<void> refresh() async {
    await loadData();
  }

  void selectBloodType(String? type) {
    _selectedBloodType = _selectedBloodType == type ? null : type;
    notifyListeners();
  }

  List<BloodBankModel> get filteredBloodBanks {
    if (_selectedBloodType == null) return _bloodBanks;
    return _bloodBanks
        .where((b) => (b.inventory[_selectedBloodType] ?? 0) > 0)
        .toList();
  }
}
