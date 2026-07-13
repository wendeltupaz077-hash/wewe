import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../../config/app_colors.dart';
import '../../config/app_routes.dart';
import '../../viewmodels/requests_viewmodel.dart';
import '../../widgets/common/empty_state.dart';
import '../../widgets/common/rounded_card.dart';
import '../../widgets/common/status_badge.dart';

class RequestHistoryScreen extends StatelessWidget {
  const RequestHistoryScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final requests = context.watch<RequestsViewModel>().myRequests;
    final dateFormat = DateFormat('MMM d, yyyy');

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(title: const Text('Request History')),
      body: requests.isEmpty
          ? const EmptyState(
              icon: Icons.assignment_outlined,
              title: 'No request history',
              subtitle: 'Your past blood requests will appear here.',
            )
          : ListView.builder(
              padding: const EdgeInsets.all(20),
              itemCount: requests.length,
              itemBuilder: (context, index) {
                final request = requests[index];
                return RoundedCard(
                  onTap: () =>
                      context.push('${AppRoutes.requestDetails}/${request.id}'),
                  margin: const EdgeInsets.only(bottom: 12),
                  child: Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(10),
                        decoration: BoxDecoration(
                          color: AppColors.primary.withValues(alpha: 0.1),
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: Text(
                          request.bloodType,
                          style: const TextStyle(
                            fontWeight: FontWeight.bold,
                            color: AppColors.primary,
                          ),
                        ),
                      ),
                      const SizedBox(width: 16),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              request.patientName,
                              style: const TextStyle(
                                fontWeight: FontWeight.w600,
                                color: AppColors.accent,
                              ),
                            ),
                            Text(
                              request.hospital,
                              style: const TextStyle(
                                fontSize: 12,
                                color: AppColors.accentLight,
                              ),
                            ),
                            Text(
                              dateFormat.format(request.requestDate),
                              style: const TextStyle(
                                fontSize: 11,
                                color: AppColors.accentLight,
                              ),
                            ),
                          ],
                        ),
                      ),
                      StatusBadge(status: request.statusLabel),
                    ],
                  ),
                );
              },
            ),
    );
  }
}
