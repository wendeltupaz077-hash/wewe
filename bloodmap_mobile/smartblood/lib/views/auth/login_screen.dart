import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../config/app_colors.dart';
import '../../config/app_routes.dart';
import '../../core/utils/validators.dart';
import '../../viewmodels/auth_viewmodel.dart';
import '../../widgets/common/auth_header.dart';
import '../../widgets/common/custom_text_field.dart';
import '../../widgets/common/primary_button.dart';
import '../../widgets/common/text_link_button.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _credentialController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _obscurePassword = true;

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
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> _login() async {
    if (!_formKey.currentState!.validate()) return;

    final vm = context.read<AuthViewModel>();
    final success = await vm.login(
      _credentialController.text,
      _passwordController.text,
    );
    if (!mounted) return;

    if (success) {
      context.push(AppRoutes.otpVerification);
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
                const AuthHeader(),
                const SizedBox(height: 32),
                const Text(
                  'Welcome back',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.w800,
                    color: AppColors.accent,
                    letterSpacing: -0.3,
                  ),
                ),
                const SizedBox(height: 8),
                const Text(
                  'Log in with your email or phone number',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 14,
                    color: AppColors.accentLight,
                    height: 1.4,
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
                  inputFormatters: _isEmailInput
                      ? null
                      : [
                          FilteringTextInputFormatter.digitsOnly,
                          LengthLimitingTextInputFormatter(10),
                        ],
                ),
                const SizedBox(height: 16),
                CustomTextField(
                  label: 'Password',
                  controller: _passwordController,
                  obscureText: _obscurePassword,
                  validator: Validators.password,
                  suffixIcon: IconButton(
                    icon: Icon(
                      _obscurePassword
                          ? Icons.visibility_off_outlined
                          : Icons.visibility_outlined,
                      color: AppColors.accentLight,
                    ),
                    onPressed: () =>
                        setState(() => _obscurePassword = !_obscurePassword),
                  ),
                ),
                Align(
                  alignment: Alignment.centerRight,
                  child: TextLinkButton(
                    label: 'Forgot Password?',
                    onPressed: () => context.push(AppRoutes.forgotPassword),
                    color: AppColors.primary,
                  ),
                ),
                const SizedBox(height: 24),
                PrimaryButton(
                  label: 'Continue',
                  isLoading: vm.isLoading,
                  onPressed: _login,
                ),
                const SizedBox(height: 16),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Text(
                      "Don't have an account? ",
                      style: TextStyle(color: AppColors.accentLight),
                    ),
                    TextLinkButton(
                      label: 'Register',
                      onPressed: () => context.push(AppRoutes.register),
                      color: AppColors.primary,
                    ),
                  ],
                ),
                const SizedBox(height: 24),
                const Text(
                  'By continuing, you agree to our Terms of Service and Privacy Policy.',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 12,
                    color: AppColors.accentLight,
                    height: 1.4,
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
