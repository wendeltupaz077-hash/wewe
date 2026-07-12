import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../../config/app_colors.dart';
import '../../config/app_routes.dart';
import '../../data/models/blood_request_model.dart';
import '../../viewmodels/requests_viewmodel.dart';
import '../../widgets/common/empty_state.dart';
import '../../widgets/common/rounded_card.dart';
import '../../widgets/common/shimmer_loading.dart';
import '../../widgets/common/status_badge.dart';

class RequestsScreen extends StatelessWidget {
  const RequestsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<RequestsViewModel>();

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Blood Requests'),
        actions: [
          IconButton(
            onPressed: () => context.push(AppRoutes.emergencyRequest),
            icon: const Icon(Icons.add_circle_outline),
          ),
        ],
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.fromLTRB(20, 8, 20, 0),
            child: SegmentedButton<int>(
              segments: const [
                ButtonSegment(value: 0, label: Text('Active')),
                ButtonSegment(value: 1, label: Text('My Requests')),
              ],
              selected: {vm.selectedTab},
              onSelectionChanged: (s) => vm.selectTab(s.first),
              style: ButtonStyle(
                backgroundColor: WidgetStateProperty.resolveWith((states) {
                  if (states.contains(WidgetState.selected)) {
                    return AppColors.primary;
                  }
                  return AppColors.surface;
                }),
                foregroundColor: WidgetStateProperty.resolveWith((states) {
                  if (states.contains(WidgetState.selected)) {
                    return Colors.white;
                  }
                  return AppColors.accent;
                }),
              ),
            ),
          ),
          Expanded(
            child: vm.isLoading
                ? const ShimmerList()
                : _RequestList(
                    requests: vm.selectedTab == 0
                        ? vm.activeRequests
                        : vm.myRequests,
                    emptyTitle: vm.selectedTab == 0
                        ? 'No active requests'
                        : 'No requests yet',
                    emptySubtitle: vm.selectedTab == 0
                        ? 'There are no active blood requests in your area.'
                        : 'Your blood requests will appear here.',
                  ),
          ),
        ],
      ),
    );
  }
}

class _RequestList extends StatelessWidget {
  const _RequestList({
    required this.requests,
    required this.emptyTitle,
    required this.emptySubtitle,
  });

  final List<BloodRequestModel> requests;
  final String emptyTitle;
  final String emptySubtitle;

  @override
  Widget build(BuildContext context) {
    if (requests.isEmpty) {
      return EmptyState(
        icon: Icons.bloodtype_outlined,
        title: emptyTitle,
        subtitle: emptySubtitle,
        actionLabel: 'Create Request',
        onAction: () => context.push(AppRoutes.emergencyRequest),
      );
    }

    return ListView.builder(
      padding: const EdgeInsets.all(20),
      itemCount: requests.length,
      itemBuilder: (context, index) {
        final request = requests[index];
        return _RequestCard(request: request);
      },
    );
  }
}

class _RequestCard extends StatelessWidget {
  const _RequestCard({required this.request});

  final BloodRequestModel request;

  @override
  Widget build(BuildContext context) {
    final dateFormat = DateFormat('MMM d, yyyy • h:mm a');

    return RoundedCard(
      onTap: () => context.push('${AppRoutes.requestDetails}/${request.id}'),
      margin: const EdgeInsets.only(bottom: 12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              if (request.isEmergency)
                Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 8,
                    vertical: 4,
                  ),
                  margin: const EdgeInsets.only(right: 8),
                  decoration: BoxDecoration(
                    color: AppColors.primary.withValues(alpha: 0.12),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: const Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Icon(Icons.emergency, size: 14, color: AppColors.primary),
                      SizedBox(width: 4),
                      Text(
                        'Emergency',
                        style: TextStyle(
                          fontSize: 11,
                          fontWeight: FontWeight.w600,
                          color: AppColors.primary,
                        ),
                      ),
                    ],
                  ),
                ),
              StatusBadge(status: request.statusLabel),
              const Spacer(),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                decoration: BoxDecoration(
                  color: AppColors.primary.withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Text(
                  request.bloodType,
                  style: const TextStyle(
                    fontWeight: FontWeight.bold,
                    color: AppColors.primary,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            request.patientName,
            style: const TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.w600,
              color: AppColors.accent,
            ),
          ),
          const SizedBox(height: 4),
          Row(
            children: [
              const Icon(Icons.local_hospital_outlined,
                  size: 16, color: AppColors.accentLight),
              const SizedBox(width: 4),
              Expanded(
                child: Text(
                  request.hospital,
                  style: const TextStyle(
                    fontSize: 13,
                    color: AppColors.accentLight,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 8),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                '${request.units} unit${request.units > 1 ? 's' : ''} needed',
                style: const TextStyle(
                  fontSize: 13,
                  color: AppColors.accent,
                  fontWeight: FontWeight.w500,
                ),
              ),
              Text(
                dateFormat.format(request.requestDate),
                style: const TextStyle(
                  fontSize: 12,
                  color: AppColors.accentLight,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
