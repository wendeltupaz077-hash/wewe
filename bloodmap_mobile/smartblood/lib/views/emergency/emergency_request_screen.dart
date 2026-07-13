import 'package:flutter/material.dart';
import '../../config/app_colors.dart';
import '../../config/constants.dart';
import '../../widgets/common/blood_type_chip.dart';
import '../../widgets/common/custom_text_field.dart';
import '../../widgets/common/primary_button.dart';
import '../../widgets/common/rounded_card.dart';

class EmergencyRequestScreen extends StatefulWidget {
  const EmergencyRequestScreen({super.key});

  @override
  State<EmergencyRequestScreen> createState() => _EmergencyRequestScreenState();
}

class _EmergencyRequestScreenState extends State<EmergencyRequestScreen> {
  final _formKey = GlobalKey<FormState>();
  final _patientController = TextEditingController();
  final _hospitalController = TextEditingController();
  final _notesController = TextEditingController();
  String? _selectedBloodType;
  int _units = 1;
  bool _isSubmitting = false;

  @override
  void dispose() {
    _patientController.dispose();
    _hospitalController.dispose();
    _notesController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    if (_selectedBloodType == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please select a blood type')),
      );
      return;
    }

    setState(() => _isSubmitting = true);
    await Future.delayed(const Duration(seconds: 1));
    if (!mounted) return;

    setState(() => _isSubmitting = false);
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('Emergency request submitted! Help is on the way.'),
        backgroundColor: AppColors.success,
      ),
    );
    Navigator.of(context).pop();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Emergency Request'),
        backgroundColor: AppColors.primary,
        foregroundColor: Colors.white,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              RoundedCard(
                color: AppColors.primary.withValues(alpha: 0.06),
                child: const Row(
                  children: [
                    Icon(Icons.emergency, color: AppColors.primary, size: 32),
                    SizedBox(width: 16),
                    Expanded(
                      child: Text(
                        'This is an emergency blood request. Nearby donors and blood banks will be notified immediately.',
                        style: TextStyle(
                          fontSize: 13,
                          color: AppColors.accent,
                          height: 1.4,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 24),
              CustomTextField(
                label: 'Patient name',
                controller: _patientController,
                validator: (v) =>
                    v == null || v.isEmpty ? 'Patient name is required' : null,
              ),
              const SizedBox(height: 16),
              CustomTextField(
                label: 'Hospital / Location',
                controller: _hospitalController,
                validator: (v) =>
                    v == null || v.isEmpty ? 'Hospital is required' : null,
              ),
              const SizedBox(height: 16),
              const Text(
                'Blood Type Needed',
                style: TextStyle(
                  fontWeight: FontWeight.w600,
                  color: AppColors.accent,
                ),
              ),
              const SizedBox(height: 12),
              Wrap(
                spacing: 8,
                runSpacing: 8,
                children: AppConstants.bloodTypes.map((type) {
                  return BloodTypeChip(
                    bloodType: type,
                    isSelected: _selectedBloodType == type,
                    onTap: () => setState(() => _selectedBloodType = type),
                  );
                }).toList(),
              ),
              const SizedBox(height: 24),
              const Text(
                'Units Needed',
                style: TextStyle(
                  fontWeight: FontWeight.w600,
                  color: AppColors.accent,
                ),
              ),
              const SizedBox(height: 8),
              Row(
                children: [
                  IconButton(
                    onPressed: _units > 1
                        ? () => setState(() => _units--)
                        : null,
                    icon: const Icon(Icons.remove_circle_outline),
                    color: AppColors.primary,
                  ),
                  Text(
                    '$_units',
                    style: const TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  IconButton(
                    onPressed: _units < 10
                        ? () => setState(() => _units++)
                        : null,
                    icon: const Icon(Icons.add_circle_outline),
                    color: AppColors.primary,
                  ),
                ],
              ),
              const SizedBox(height: 16),
              CustomTextField(
                label: 'Additional notes (optional)',
                controller: _notesController,
                maxLines: 3,
              ),
              const SizedBox(height: 32),
              PrimaryButton(
                label: 'Submit Emergency Request',
                icon: Icons.emergency,
                isLoading: _isSubmitting,
                onPressed: _submit,
              ),
            ],
          ),
        ),
      ),
    );
  }
}
