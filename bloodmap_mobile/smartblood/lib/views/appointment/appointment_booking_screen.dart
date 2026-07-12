import 'package:flutter/material.dart';
import '../../config/app_colors.dart';
import '../../data/mock/mock_data.dart';
import '../../widgets/common/custom_text_field.dart';
import '../../widgets/common/primary_button.dart';
import '../../widgets/common/rounded_card.dart';

class AppointmentBookingScreen extends StatefulWidget {
  const AppointmentBookingScreen({super.key});

  @override
  State<AppointmentBookingScreen> createState() =>
      _AppointmentBookingScreenState();
}

class _AppointmentBookingScreenState extends State<AppointmentBookingScreen> {
  String? _selectedBank;
  DateTime _selectedDate = DateTime.now().add(const Duration(days: 1));
  String _selectedTime = '09:00 AM';
  final _notesController = TextEditingController();
  bool _isSubmitting = false;

  final _timeSlots = [
    '09:00 AM', '10:00 AM', '11:00 AM',
    '01:00 PM', '02:00 PM', '03:00 PM', '04:00 PM',
  ];

  @override
  void dispose() {
    _notesController.dispose();
    super.dispose();
  }

  Future<void> _book() async {
    if (_selectedBank == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please select a blood bank')),
      );
      return;
    }

    setState(() => _isSubmitting = true);
    await Future.delayed(const Duration(seconds: 1));
    if (!mounted) return;

    setState(() => _isSubmitting = false);
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Appointment booked successfully!')),
    );
    Navigator.of(context).pop();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(title: const Text('Book Appointment')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Select Blood Bank',
              style: TextStyle(
                fontWeight: FontWeight.w600,
                color: AppColors.accent,
              ),
            ),
            const SizedBox(height: 12),
            ...MockData.bloodBanks.map((bank) {
              return RoundedCard(
                onTap: () => setState(() => _selectedBank = bank.id),
                margin: const EdgeInsets.only(bottom: 8),
                color: _selectedBank == bank.id
                    ? AppColors.primary.withValues(alpha: 0.06)
                    : null,
                child: Row(
                  children: [
                    Icon(
                      Icons.local_hospital_outlined,
                      color: _selectedBank == bank.id
                          ? AppColors.primary
                          : AppColors.accentLight,
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            bank.name,
                            style: const TextStyle(fontWeight: FontWeight.w600),
                          ),
                          Text(
                            bank.distance,
                            style: const TextStyle(
                              fontSize: 12,
                              color: AppColors.accentLight,
                            ),
                          ),
                        ],
                      ),
                    ),
                    if (_selectedBank == bank.id)
                      const Icon(Icons.check_circle, color: AppColors.primary),
                  ],
                ),
              );
            }),
            const SizedBox(height: 24),
            const Text(
              'Select Date',
              style: TextStyle(
                fontWeight: FontWeight.w600,
                color: AppColors.accent,
              ),
            ),
            const SizedBox(height: 12),
            RoundedCard(
              onTap: () async {
                final date = await showDatePicker(
                  context: context,
                  initialDate: _selectedDate,
                  firstDate: DateTime.now(),
                  lastDate: DateTime.now().add(const Duration(days: 90)),
                );
                if (date != null) setState(() => _selectedDate = date);
              },
              child: Row(
                children: [
                  const Icon(Icons.calendar_today, color: AppColors.primary),
                  const SizedBox(width: 12),
                  Text(
                    '${_selectedDate.day}/${_selectedDate.month}/${_selectedDate.year}',
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),
            const Text(
              'Select Time',
              style: TextStyle(
                fontWeight: FontWeight.w600,
                color: AppColors.accent,
              ),
            ),
            const SizedBox(height: 12),
            Wrap(
              spacing: 8,
              runSpacing: 8,
              children: _timeSlots.map((time) {
                final isSelected = _selectedTime == time;
                return ChoiceChip(
                  label: Text(time),
                  selected: isSelected,
                  onSelected: (_) => setState(() => _selectedTime = time),
                  selectedColor: AppColors.primary.withValues(alpha: 0.15),
                  labelStyle: TextStyle(
                    color: isSelected ? AppColors.primary : AppColors.accent,
                    fontWeight:
                        isSelected ? FontWeight.w600 : FontWeight.normal,
                  ),
                );
              }).toList(),
            ),
            const SizedBox(height: 24),
            CustomTextField(
              label: 'Notes (optional)',
              controller: _notesController,
              maxLines: 2,
            ),
            const SizedBox(height: 32),
            PrimaryButton(
              label: 'Book Appointment',
              icon: Icons.event_available,
              isLoading: _isSubmitting,
              onPressed: _book,
            ),
          ],
        ),
      ),
    );
  }
}
