class BloodRequestModel {
  const BloodRequestModel({
    required this.id,
    required this.patientName,
    required this.bloodType,
    required this.units,
    required this.hospital,
    required this.status,
    required this.requestDate,
    required this.isEmergency,
    this.notes,
    this.isMine = false,
  });

  final String id;
  final String patientName;
  final String bloodType;
  final int units;
  final String hospital;
  final String status;
  final DateTime requestDate;
  final bool isEmergency;
  final String? notes;
  final bool isMine;

  String get statusLabel {
    switch (status) {
      case 'pending':
        return 'Pending';
      case 'processing':
        return 'Processing';
      case 'approved':
        return 'Approved';
      case 'fulfilled':
        return 'Fulfilled';
      case 'cancelled':
        return 'Cancelled';
      default:
        return status;
    }
  }
}
