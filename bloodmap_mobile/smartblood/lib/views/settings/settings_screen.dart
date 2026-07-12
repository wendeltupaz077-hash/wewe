import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../config/app_colors.dart';
import '../../config/app_routes.dart';
import '../../widgets/common/rounded_card.dart';

class SettingsScreen extends StatefulWidget {
  const SettingsScreen({super.key});

  @override
  State<SettingsScreen> createState() => _SettingsScreenState();
}

class _SettingsScreenState extends State<SettingsScreen> {
  bool _notifications = true;
  bool _emergencyAlerts = true;
  bool _locationServices = true;
  bool _darkMode = false;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(title: const Text('Settings')),
      body: ListView(
        padding: const EdgeInsets.all(20),
        children: [
          _SettingsGroup(
            title: 'Notifications',
            children: [
              _SettingsSwitch(
                title: 'Push Notifications',
                subtitle: 'Receive app notifications',
                value: _notifications,
                onChanged: (v) => setState(() => _notifications = v),
              ),
              _SettingsSwitch(
                title: 'Emergency Alerts',
                subtitle: 'Urgent blood request alerts',
                value: _emergencyAlerts,
                onChanged: (v) => setState(() => _emergencyAlerts = v),
              ),
            ],
          ),
          const SizedBox(height: 16),
          _SettingsGroup(
            title: 'Privacy & Location',
            children: [
              _SettingsSwitch(
                title: 'Location Services',
                subtitle: 'Find nearby blood banks and donors',
                value: _locationServices,
                onChanged: (v) => setState(() => _locationServices = v),
              ),
            ],
          ),
          const SizedBox(height: 16),
          _SettingsGroup(
            title: 'Appearance',
            children: [
              _SettingsSwitch(
                title: 'Dark Mode',
                subtitle: 'Switch to dark theme',
                value: _darkMode,
                onChanged: (v) => setState(() => _darkMode = v),
              ),
            ],
          ),
          const SizedBox(height: 16),
          _SettingsGroup(
            title: 'Legal',
            children: [
              _SettingsTile(
                title: 'Privacy Policy',
                onTap: () => context.push(AppRoutes.privacyPolicy),
              ),
              _SettingsTile(
                title: 'About Blood Map PH',
                onTap: () => context.push(AppRoutes.about),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class _SettingsGroup extends StatelessWidget {
  const _SettingsGroup({required this.title, required this.children});

  final String title;
  final List<Widget> children;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.only(left: 4, bottom: 8),
          child: Text(
            title,
            style: const TextStyle(
              fontSize: 14,
              fontWeight: FontWeight.w600,
              color: AppColors.accentLight,
            ),
          ),
        ),
        RoundedCard(padding: EdgeInsets.zero, child: Column(children: children)),
      ],
    );
  }
}

class _SettingsSwitch extends StatelessWidget {
  const _SettingsSwitch({
    required this.title,
    required this.subtitle,
    required this.value,
    required this.onChanged,
  });

  final String title;
  final String subtitle;
  final bool value;
  final ValueChanged<bool> onChanged;

  @override
  Widget build(BuildContext context) {
    return SwitchListTile(
      title: Text(title,
          style: const TextStyle(fontWeight: FontWeight.w500)),
      subtitle: Text(subtitle,
          style: const TextStyle(fontSize: 12, color: AppColors.accentLight)),
      value: value,
      onChanged: onChanged,
      activeTrackColor: AppColors.primary.withValues(alpha: 0.5),
    );
  }
}

class _SettingsTile extends StatelessWidget {
  const _SettingsTile({required this.title, required this.onTap});

  final String title;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return ListTile(
      title: Text(title),
      trailing: const Icon(Icons.chevron_right, color: AppColors.accentLight),
      onTap: onTap,
    );
  }
}
