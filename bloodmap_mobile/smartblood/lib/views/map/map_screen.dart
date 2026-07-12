import 'package:flutter/material.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:go_router/go_router.dart';
import 'package:latlong2/latlong.dart';
import 'package:provider/provider.dart';
import '../../config/app_colors.dart';
import '../../config/app_routes.dart';
import '../../data/mock/mock_data.dart';
import '../../viewmodels/map_viewmodel.dart';
import '../../widgets/common/rounded_card.dart';
import '../../widgets/common/shimmer_loading.dart';

class MapScreen extends StatelessWidget {
  const MapScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<MapViewModel>();

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Blood Map'),
        actions: [
          IconButton(
            onPressed: () {},
            icon: const Icon(Icons.my_location),
            tooltip: 'Current Location',
          ),
        ],
      ),
      body: vm.isLoading
          ? const Center(child: ShimmerLoading(height: 300))
          : Stack(
              children: [
                FlutterMap(
                  options: MapOptions(
                    initialCenter: const LatLng(14.6349, 121.0344),
                    initialZoom: 13,
                  ),
                  children: [
                    TileLayer(
                      urlTemplate: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                      userAgentPackageName: 'com.smartblood.app',
                    ),
                    MarkerLayer(
                      markers: [
                        if (vm.showBloodBanks)
                          ...MockData.bloodBanks.map(
                            (bank) => Marker(
                              point: LatLng(bank.latitude, bank.longitude),
                              width: 80,
                              height: 80,
                              child: GestureDetector(
                                onTap: () => context.push(
                                  '${AppRoutes.bloodBankDetails}/${bank.id}',
                                ),
                                child: Column(
                                  children: [
                                    Container(
                                      padding: const EdgeInsets.all(8),
                                      decoration: BoxDecoration(
                                        color: AppColors.primary,
                                        shape: BoxShape.circle,
                                        boxShadow: [
                                          BoxShadow(
                                            color:
                                                AppColors.primary.withValues(alpha: 0.4),
                                            blurRadius: 8,
                                            offset: const Offset(0, 3),
                                          ),
                                        ],
                                      ),
                                      child: const Icon(
                                        Icons.local_hospital,
                                        color: Colors.white,
                                        size: 24,
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                            ),
                          ),
                        if (vm.showDonors)
                          ...MockData.donors.map(
                            (donor) => Marker(
                              point: LatLng(donor.latitude, donor.longitude),
                              width: 80,
                              height: 80,
                              child: GestureDetector(
                                onTap: () => context.push(
                                  '${AppRoutes.donorDetails}/${donor.id}',
                                ),
                                child: Column(
                                  children: [
                                    Container(
                                      padding: const EdgeInsets.all(8),
                                      decoration: BoxDecoration(
                                        color: AppColors.success,
                                        shape: BoxShape.circle,
                                        boxShadow: [
                                          BoxShadow(
                                            color:
                                                AppColors.success.withValues(alpha: 0.4),
                                            blurRadius: 8,
                                            offset: const Offset(0, 3),
                                          ),
                                        ],
                                      ),
                                      child: const Icon(
                                        Icons.person,
                                        color: Colors.white,
                                        size: 24,
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                            ),
                          ),
                        Marker(
                          point: const LatLng(14.6300, 121.0350),
                          width: 80,
                          height: 80,
                          child: Container(
                            padding: const EdgeInsets.all(8),
                            decoration: BoxDecoration(
                              color: const Color(0xFF0077B6),
                              shape: BoxShape.circle,
                              boxShadow: [
                                BoxShadow(
                                  color:
                                      const Color(0xFF0077B6).withValues(alpha: 0.4),
                                  blurRadius: 8,
                                  offset: const Offset(0, 3),
                                ),
                              ],
                              border: Border.all(color: Colors.white, width: 3),
                            ),
                            child: const Icon(
                              Icons.my_location,
                              color: Colors.white,
                              size: 24,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
                Positioned(
                  top: 16,
                  left: 16,
                  right: 16,
                  child: RoundedCard(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 16,
                      vertical: 12,
                    ),
                    child: Row(
                      children: [
                        FilterChip(
                          label: const Text('Blood Banks'),
                          selected: vm.showBloodBanks,
                          onSelected: vm.toggleBloodBanks,
                          selectedColor:
                              AppColors.primary.withValues(alpha: 0.15),
                          checkmarkColor: AppColors.primary,
                        ),
                        const SizedBox(width: 8),
                        FilterChip(
                          label: const Text('Donors'),
                          selected: vm.showDonors,
                          onSelected: vm.toggleDonors,
                          selectedColor:
                              AppColors.primary.withValues(alpha: 0.15),
                          checkmarkColor: AppColors.primary,
                        ),
                      ],
                    ),
                  ),
                ),
                Positioned(
                  bottom: 0,
                  left: 0,
                  right: 0,
                  child: Container(
                    height: 220,
                    decoration: const BoxDecoration(
                      color: AppColors.surface,
                      borderRadius: BorderRadius.vertical(
                        top: Radius.circular(24),
                      ),
                      boxShadow: [
                        BoxShadow(
                          color: Color(0x1A000000),
                          blurRadius: 16,
                          offset: Offset(0, -4),
                        ),
                      ],
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Center(
                          child: Container(
                            margin: const EdgeInsets.only(top: 12),
                            width: 40,
                            height: 4,
                            decoration: BoxDecoration(
                              color: AppColors.border,
                              borderRadius: BorderRadius.circular(2),
                            ),
                          ),
                        ),
                        const Padding(
                          padding: EdgeInsets.fromLTRB(20, 12, 20, 8),
                          child: Text(
                            'Nearby Locations',
                            style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              color: AppColors.accent,
                            ),
                          ),
                        ),
                        Expanded(
                          child: ListView(
                            scrollDirection: Axis.horizontal,
                            padding: const EdgeInsets.symmetric(horizontal: 20),
                            children: [
                              if (vm.showBloodBanks)
                                ...MockData.bloodBanks.map(
                                  (bank) => _MapLocationCard(
                                    icon: Icons.local_hospital,
                                    title: bank.name,
                                    subtitle: bank.distance,
                                    color: AppColors.primary,
                                    onTap: () => context.push(
                                      '${AppRoutes.bloodBankDetails}/${bank.id}',
                                    ),
                                  ),
                                ),
                              if (vm.showDonors)
                                ...MockData.donors.map(
                                  (donor) => _MapLocationCard(
                                    icon: Icons.person,
                                    title: donor.name,
                                    subtitle:
                                        '${donor.bloodType} • ${donor.distance}',
                                    color: AppColors.success,
                                    onTap: () => context.push(
                                      '${AppRoutes.donorDetails}/${donor.id}',
                                    ),
                                  ),
                                ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {},
        backgroundColor: AppColors.primary,
        child: const Icon(Icons.navigation, color: Colors.white),
      ),
    );
  }
}

class _MapLocationCard extends StatelessWidget {
  const _MapLocationCard({
    required this.icon,
    required this.title,
    required this.subtitle,
    required this.color,
    required this.onTap,
  });

  final IconData icon;
  final String title;
  final String subtitle;
  final Color color;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 200,
        margin: const EdgeInsets.only(right: 12),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: AppColors.background,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: AppColors.border),
        ),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: color.withValues(alpha: 0.12),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Icon(icon, color: color, size: 20),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(
                      fontWeight: FontWeight.w600,
                      fontSize: 13,
                    ),
                  ),
                  Text(
                    subtitle,
                    style: const TextStyle(
                      fontSize: 12,
                      color: AppColors.accentLight,
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
