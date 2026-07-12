class AnnouncementModel {
  const AnnouncementModel({
    required this.id,
    required this.title,
    required this.description,
    required this.date,
    this.imageIcon = 'campaign',
  });

  final String id;
  final String title;
  final String description;
  final DateTime date;
  final String imageIcon;
}

class DonorModel {
  const DonorModel({
    required this.id,
    required this.name,
    required this.bloodType,
    required this.distance,
    required this.isAvailable,
    required this.lastDonation,
    required this.latitude,
    required this.longitude,
  });

  final String id;
  final String name;
  final String bloodType;
  final String distance;
  final bool isAvailable;
  final DateTime lastDonation;
  final double latitude;
  final double longitude;
}

class DonationRecord {
  const DonationRecord({
    required this.id,
    required this.date,
    required this.bloodType,
    required this.location,
    required this.units,
  });

  final String id;
  final DateTime date;
  final String bloodType;
  final String location;
  final int units;
}
