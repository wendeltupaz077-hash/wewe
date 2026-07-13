import '../models/announcement_model.dart';
import '../models/blood_bank_model.dart';
import '../models/blood_request_model.dart';
import '../models/notification_model.dart';
import '../models/user_model.dart';

class MockData {
  MockData._();

  static const user = UserModel(
    id: '1',
    firstName: 'Maria',
    lastName: 'Santos',
    middleName: 'Cruz',
    email: 'maria.santos@email.com',
    phone: '+63 917 123 4567',
    bloodType: 'O+',
    address: 'Quezon City, Metro Manila',
  );

  static final bloodBanks = [
    BloodBankModel(
      id: 'bb1',
      name: 'Philippine Red Cross - QC',
      address: 'E. Rodriguez Sr. Ave, Quezon City',
      distance: '1.2 km',
      phone: '+63 2 8790 2300',
      inventory: {'O+': 12, 'O-': 5, 'A+': 8, 'B+': 6, 'AB+': 3},
      isOpen: true,
      rating: 4.8,
      latitude: 14.6349,
      longitude: 121.0344,
    ),
    BloodBankModel(
      id: 'bb2',
      name: 'St. Luke\'s Medical Center',
      address: '279 E Rodriguez Sr Ave, Quezon City',
      distance: '2.5 km',
      phone: '+63 2 8723 0101',
      inventory: {'O+': 20, 'A+': 15, 'B+': 10, 'AB+': 4, 'O-': 7},
      isOpen: true,
      rating: 4.9,
      latitude: 14.6225,
      longitude: 121.0242,
    ),
    BloodBankModel(
      id: 'bb3',
      name: 'East Avenue Medical Center',
      address: 'East Ave, Diliman, Quezon City',
      distance: '3.1 km',
      phone: '+63 2 8928 0611',
      inventory: {'O+': 6, 'A+': 4, 'B+': 2, 'AB-': 1},
      isOpen: false,
      rating: 4.5,
      latitude: 14.6412,
      longitude: 121.0498,
    ),
  ];

  static final activeRequests = [
    BloodRequestModel(
      id: 'req1',
      patientName: 'Juan Dela Cruz',
      bloodType: 'O-',
      units: 2,
      hospital: 'Philippine General Hospital',
      status: 'processing',
      requestDate: DateTime.now().subtract(const Duration(hours: 2)),
      isEmergency: true,
    ),
    BloodRequestModel(
      id: 'req2',
      patientName: 'Ana Reyes',
      bloodType: 'A+',
      units: 1,
      hospital: 'Manila Doctors Hospital',
      status: 'pending',
      requestDate: DateTime.now().subtract(const Duration(hours: 5)),
      isEmergency: false,
    ),
  ];

  static final myRequests = [
    BloodRequestModel(
      id: 'myreq1',
      patientName: 'Carlos Mendoza',
      bloodType: 'B+',
      units: 1,
      hospital: 'St. Luke\'s Medical Center',
      status: 'approved',
      requestDate: DateTime.now().subtract(const Duration(days: 1)),
      isEmergency: false,
      isMine: true,
    ),
    BloodRequestModel(
      id: 'myreq2',
      patientName: 'Rosa Villanueva',
      bloodType: 'AB-',
      units: 2,
      hospital: 'East Avenue Medical Center',
      status: 'fulfilled',
      requestDate: DateTime.now().subtract(const Duration(days: 7)),
      isEmergency: true,
      isMine: true,
    ),
  ];

  static final notifications = [
    NotificationModel(
      id: 'n1',
      title: 'Emergency Blood Request',
      message: 'Urgent need for O- blood at PGH. 2 units required immediately.',
      type: NotificationType.emergency,
      timestamp: DateTime.now().subtract(const Duration(minutes: 15)),
    ),
    NotificationModel(
      id: 'n2',
      title: 'Donation Request Nearby',
      message: 'A donor with B+ blood type is needed within 5km of your location.',
      type: NotificationType.donation,
      timestamp: DateTime.now().subtract(const Duration(hours: 1)),
    ),
    NotificationModel(
      id: 'n3',
      title: 'Blood Availability Update',
      message: 'O+ blood is now available at Philippine Red Cross - QC.',
      type: NotificationType.availability,
      timestamp: DateTime.now().subtract(const Duration(hours: 3)),
      isRead: true,
    ),
    NotificationModel(
      id: 'n4',
      title: 'System Maintenance',
      message: 'Scheduled maintenance on July 15, 2026 from 2:00 AM to 4:00 AM.',
      type: NotificationType.system,
      timestamp: DateTime.now().subtract(const Duration(days: 1)),
      isRead: true,
    ),
  ];

  static final announcements = [
    AnnouncementModel(
      id: 'a1',
      title: 'National Blood Donor Month',
      description: 'Join our blood donation drive this July. Every drop saves a life.',
      date: DateTime.now().subtract(const Duration(days: 2)),
    ),
    AnnouncementModel(
      id: 'a2',
      title: 'New Partner Blood Bank',
      description: 'St. Luke\'s Medical Center is now connected to Blood Map PH.',
      date: DateTime.now().subtract(const Duration(days: 5)),
    ),
  ];

  static final donors = [
    DonorModel(
      id: 'd1',
      name: 'Pedro Garcia',
      bloodType: 'O+',
      distance: '0.8 km',
      isAvailable: true,
      lastDonation: DateTime.now().subtract(const Duration(days: 90)),
      latitude: 14.6320,
      longitude: 121.0380,
    ),
    DonorModel(
      id: 'd2',
      name: 'Lisa Fernandez',
      bloodType: 'A-',
      distance: '1.5 km',
      isAvailable: true,
      lastDonation: DateTime.now().subtract(const Duration(days: 120)),
      latitude: 14.6280,
      longitude: 121.0310,
    ),
  ];

  static final donationHistory = [
    DonationRecord(
      id: 'dh1',
      date: DateTime(2026, 3, 15),
      bloodType: 'O+',
      location: 'Philippine Red Cross - QC',
      units: 1,
    ),
    DonationRecord(
      id: 'dh2',
      date: DateTime(2025, 12, 10),
      bloodType: 'O+',
      location: 'St. Luke\'s Medical Center',
      units: 1,
    ),
  ];
}
