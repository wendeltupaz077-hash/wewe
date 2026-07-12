import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../config/app_colors.dart';
import '../../core/utils/validators.dart';
import '../../viewmodels/auth_viewmodel.dart';
import '../../widgets/common/auth_header.dart';
import '../../widgets/common/custom_text_field.dart';
import '../../widgets/common/primary_button.dart';

class ForgotPasswordScreen extends StatefulWidget {
  const ForgotPasswordScreen({super.key});

  @override
  State<ForgotPasswordScreen> createState() => _ForgotPasswordScreenState();
}

class _ForgotPasswordScreenState extends State<ForgotPasswordScreen> {
  final _formKey = GlobalKey<FormState>();
  final _credentialController = TextEditingController();

  bool get _isEmailInput {
    final text = _credentialController.text.trim();
    return text.isNotEmpty && AuthViewModel.isEmailCredential(text);
  }

  @override
  void initState() {
    super.initState();
    _credentialController.addListener(() => setState(() {}));
  }

  @override
  void dispose() {
    _credentialController.dispose();
    super.dispose();
  }

  Future<void> _reset() async {
    if (!_formKey.currentState!.validate()) return;

    final vm = context.read<AuthViewModel>();
    final success = await vm.resetPassword(_credentialController.text);
    if (!mounted) return;

    if (success) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            _isEmailInput
                ? 'Password reset link sent to your email'
                : 'Password reset code sent via SMS',
          ),
        ),
      );
      context.pop();
    } else if (vm.error != null) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(vm.error!)),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<AuthViewModel>();

    return Scaffold(
      backgroundColor: AppColors.surface,
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 24),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                AuthHeader(
                  showBack: true,
                  onBack: () => context.pop(),
                ),
                const SizedBox(height: 48),
                Container(
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    gradient: LinearGradient(
                      begin: Alignment.topLeft,
                      end: Alignment.bottomRight,
                      colors: [
                        AppColors.primary.withValues(alpha: 0.14),
                        AppColors.primary.withValues(alpha: 0.06),
                      ],
                    ),
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(
                    Icons.lock_reset,
                    size: 48,
                    color: AppColors.primary,
                  ),
                ),
                const SizedBox(height: 32),
                const Text(
                  'Forgot Password?',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.w800,
                    color: AppColors.accent,
                    letterSpacing: -0.3,
                  ),
                ),
                const SizedBox(height: 12),
                Text(
                  _isEmailInput
                      ? 'Enter your email address and we will send you a link to reset your password.'
                      : 'Enter your phone number and we will send you a code to reset your password.',
                  textAlign: TextAlign.center,
                  style: const TextStyle(
                    fontSize: 14,
                    color: AppColors.accentLight,
                    height: 1.5,
                  ),
                ),
                const SizedBox(height: 32),
                CustomTextField(
                  label: _isEmailInput ? 'Email address' : 'Phone number',
                  controller: _credentialController,
                  keyboardType: _isEmailInput
                      ? TextInputType.emailAddress
                      : TextInputType.phone,
                  validator: Validators.emailOrPhone,
                  prefixIcon: _isEmailInput
                      ? const Icon(
                          Icons.email_outlined,
                          color: AppColors.accentLight,
                          size: 20,
                        )
                      : const Padding(
                          padding: EdgeInsets.only(left: 16, right: 8),
                          child: Text(
                            '+63',
                            style: TextStyle(
                              color: AppColors.accent,
                              fontSize: 16,
                              fontWeight: FontWeight.w500,
                            ),
                          ),
                        ),
                ),
                const SizedBox(height: 32),
                PrimaryButton(
                  label: _isEmailInput ? 'Send Reset Link' : 'Send Reset Code',
                  isLoading: vm.isLoading,
                  onPressed: _reset,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
