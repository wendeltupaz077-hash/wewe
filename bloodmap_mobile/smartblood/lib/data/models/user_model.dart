class UserModel {
  const UserModel({
    required this.id,
    required this.firstName,
    required this.lastName,
    this.middleName,
    required this.email,
    required this.phone,
    this.bloodType,
    this.address,
    this.avatarUrl,
  });

  final String id;
  final String firstName;
  final String lastName;
  final String? middleName;
  final String email;
  final String phone;
  final String? bloodType;
  final String? address;
  final String? avatarUrl;

  String get fullName {
    if (middleName != null && middleName!.isNotEmpty) {
      return '$firstName $middleName $lastName';
    }
    return '$firstName $lastName';
  }

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'].toString(),
      firstName: json['first_name']?.toString() ?? '',
      lastName: json['last_name']?.toString() ?? '',
      middleName: json['middle_name']?.toString(),
      email: json['email']?.toString() ?? '',
      phone: json['phone']?.toString() ?? '',
      bloodType: json['blood_type']?.toString(),
      address: json['address']?.toString(),
      avatarUrl: json['avatar_url']?.toString(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'first_name': firstName,
      'last_name': lastName,
      'middle_name': middleName,
      'email': email,
      'phone': phone,
      'blood_type': bloodType,
      'address': address,
      'avatar_url': avatarUrl,
    };
  }

  UserModel copyWith({
    String? firstName,
    String? lastName,
    String? middleName,
    String? email,
    String? phone,
    String? bloodType,
    String? address,
    String? avatarUrl,
  }) {
    return UserModel(
      id: id,
      firstName: firstName ?? this.firstName,
      lastName: lastName ?? this.lastName,
      middleName: middleName ?? this.middleName,
      email: email ?? this.email,
      phone: phone ?? this.phone,
      bloodType: bloodType ?? this.bloodType,
      address: address ?? this.address,
      avatarUrl: avatarUrl ?? this.avatarUrl,
    );
  }
}
