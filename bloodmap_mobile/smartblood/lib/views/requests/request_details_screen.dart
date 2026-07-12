import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../../config/app_colors.dart';
import '../../viewmodels/requests_viewmodel.dart';
import '../../widgets/common/primary_button.dart';
import '../../widgets/common/rounded_card.dart';
import '../../widgets/common/status_badge.dart';

class RequestDetailsScreen extends StatelessWidget {
  const RequestDetailsScreen({super.key, required this.id});

  final String id;

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<RequestsViewModel>();
    final request = vm.getRequestById(id);

    if (request == null) {
      return Scaffold(
        appBar: AppBar(title: const Text('Request Details')),
        body: const Center(child: Text('Request not found')),
      );
    }

    final dateFormat = DateFormat('MMMM d, yyyy • h:mm a');
    final steps = ['Submitted', 'Processing', 'Approved', 'Fulfilled'];
    final currentStep = _getStepIndex(request.status);

    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Request Details'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            RoundedCard(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      StatusBadge(status: request.statusLabel),
                      const Spacer(),
                      if (request.isEmergency)
                        const Icon(Icons.emergency, color: AppColors.primary),
                    ],
                  ),
                  const SizedBox(height: 16),
                  Text(
                    request.patientName,
                    style: const TextStyle(
                      fontSize: 22,
                      fontWeight: FontWeight.bold,
                      color: AppColors.accent,
                    ),
                  ),
                  const SizedBox(height: 8),
                  _DetailRow(
                    icon: Icons.bloodtype,
                    label: 'Blood Type',
                    value: request.bloodType,
                  ),
                  _DetailRow(
                    icon: Icons.water_drop_outlined,
                    label: 'Units Needed',
                    value: '${request.units}',
                  ),
                  _DetailRow(
                    icon: Icons.local_hospital_outlined,
                    label: 'Hospital',
                    value: request.hospital,
                  ),
                  _DetailRow(
                    icon: Icons.access_time,
                    label: 'Requested',
                    value: dateFormat.format(request.requestDate),
                  ),
                  if (request.notes != null)
                    _DetailRow(
                      icon: Icons.notes,
                      label: 'Notes',
                      value: request.notes!,
                    ),
                ],
              ),
            ),
            const SizedBox(height: 24),
            const Text(
              'Status Tracking',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: AppColors.accent,
              ),
            ),
            const SizedBox(height: 16),
            RoundedCard(
              child: Column(
                children: List.generate(steps.length, (index) {
                  final isCompleted = index <= currentStep;
                  final isActive = index == currentStep;
                  return _TimelineStep(
                    title: steps[index],
                    isCompleted: isCompleted,
                    isActive: isActive,
                    isLast: index == steps.length - 1,
                  );
                }),
              ),
            ),
            const SizedBox(height: 32),
            if (request.status != 'fulfilled' &&
                request.status != 'cancelled')
              PrimaryButton(
                label: 'Contact Blood Bank',
                icon: Icons.phone,
                onPressed: () {},
              ),
          ],
        ),
      ),
    );
  }

  int _getStepIndex(String status) {
    switch (status) {
      case 'pending':
        return 0;
      case 'processing':
        return 1;
      case 'approved':
        return 2;
      case 'fulfilled':
        return 3;
      default:
        return 0;
    }
  }
}

class _DetailRow extends StatelessWidget {
  const _DetailRow({
    required this.icon,
    required this.label,
    required this.value,
  });

  final IconData icon;
  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(top: 12),
      child: Row(
        children: [
          Icon(icon, size: 20, color: AppColors.primary),
          const SizedBox(width: 12),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                label,
                style: const TextStyle(
                  fontSize: 12,
                  color: AppColors.accentLight,
                ),
              ),
              Text(
                value,
                style: const TextStyle(
                  fontSize: 15,
                  fontWeight: FontWeight.w500,
                  color: AppColors.accent,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class _TimelineStep extends StatelessWidget {
  const _TimelineStep({
    required this.title,
    required this.isCompleted,
    required this.isActive,
    required this.isLast,
  });

  final String title;
  final bool isCompleted;
  final bool isActive;
  final bool isLast;

  @override
  Widget build(BuildContext context) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Column(
          children: [
            Container(
              width: 24,
              height: 24,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: isCompleted ? AppColors.primary : AppColors.border,
                border: isActive
                    ? Border.all(color: AppColors.primary, width: 2)
                    : null,
              ),
              child: isCompleted
                  ? const Icon(Icons.check, size: 14, color: Colors.white)
                  : null,
            ),
            if (!isLast)
              Container(
                width: 2,
                height: 32,
                color: isCompleted ? AppColors.primary : AppColors.border,
              ),
          ],
        ),
        const SizedBox(width: 16),
        Expanded(
          child: Padding(
            padding: const EdgeInsets.only(bottom: 24),
            child: Text(
              title,
              style: TextStyle(
                fontWeight: isActive ? FontWeight.w600 : FontWeight.normal,
                color: isCompleted ? AppColors.accent : AppColors.accentLight,
              ),
            ),
          ),
        ),
      ],
    );
  }
}
