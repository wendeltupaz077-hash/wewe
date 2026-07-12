import 'package:flutter/foundation.dart';
import '../data/mock/mock_data.dart';
import '../data/models/blood_request_model.dart';

class RequestsViewModel extends ChangeNotifier {
  int _selectedTab = 0;
  bool _isLoading = true;
  List<BloodRequestModel> _activeRequests = [];
  List<BloodRequestModel> _myRequests = [];

  int get selectedTab => _selectedTab;
  bool get isLoading => _isLoading;
  List<BloodRequestModel> get activeRequests => _activeRequests;
  List<BloodRequestModel> get myRequests => _myRequests;

  Future<void> loadData() async {
    _isLoading = true;
    notifyListeners();

    await Future.delayed(const Duration(milliseconds: 600));

    _activeRequests = MockData.activeRequests;
    _myRequests = MockData.myRequests;
    _isLoading = false;
    notifyListeners();
  }

  void selectTab(int index) {
    _selectedTab = index;
    notifyListeners();
  }

  BloodRequestModel? getRequestById(String id) {
    try {
      return [..._activeRequests, ..._myRequests].firstWhere((r) => r.id == id);
    } catch (_) {
      return null;
    }
  }
}
