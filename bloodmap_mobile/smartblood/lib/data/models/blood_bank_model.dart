class BloodBankModel {
  const BloodBankModel({
    required this.id,
    required this.name,
    required this.address,
    required this.distance,
    required this.phone,
    required this.inventory,
    required this.isOpen,
    required this.rating,
    required this.latitude,
    required this.longitude,
  });

  final String id;
  final String name;
  final String address;
  final String distance;
  final String phone;
  final Map<String, int> inventory;
  final bool isOpen;
  final double rating;
  final double latitude;
  final double longitude;

  int get totalUnits =>
      inventory.values.fold(0, (sum, units) => sum + units);
}
