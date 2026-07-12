import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../config/app_colors.dart';
import '../../config/app_routes.dart';
import '../../core/utils/validators.dart';
import '../../viewmodels/auth_viewmodel.dart';
import '../../widgets/common/auth_header.dart';
import '../../widgets/common/auth_method_selector.dart';
import '../../widgets/common/custom_text_field.dart';
import '../../widgets/common/primary_button.dart';
import '../../widgets/common/text_link_button.dart';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final _formKey = GlobalKey<FormState>();
  final _firstNameController = TextEditingController();
  final _lastNameController = TextEditingController();
  final _middleNameController = TextEditingController();
  final _emailController = TextEditingController();
  final _phoneController = TextEditingController();
  final _passwordController = TextEditingController();
  AuthContactMethod _method = AuthContactMethod.email;
  bool _noMiddleName = false;
  bool _obscurePassword = true;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      _listenForErrors();
    });
  }

  void _listenForErrors() {
    final vm = context.read<AuthViewModel>();
    vm.addListener(() {
      if (mounted && vm.error != null) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(vm.error!),
            backgroundColor: AppColors.error,
            duration: const Duration(seconds: 3),
          ),
        );
        vm.clearError();
      }
    });
  }

  @override
  void dispose() {
    _firstNameController.dispose();
    _lastNameController.dispose();
    _middleNameController.dispose();
    _emailController.dispose();
    _phoneController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  void _onMethodChanged(AuthContactMethod method) {
    if (_method == method) return;
    setState(() => _method = method);
  }

  Future<void> _register() async {
    if (!_formKey.currentState!.validate()) return;

    final vm = context.read<AuthViewModel>();
    final success = await vm.register(
      firstName: _firstNameController.text,
      lastName: _lastNameController.text,
      middleName: _noMiddleName ? null : _middleNameController.text,
      email: _method == AuthContactMethod.email ? _emailController.text : null,
      phone: _method == AuthContactMethod.phone ? _phoneController.text : null,
      password: _passwordController.text,
      method: _method,
    );
    if (!mounted) return;

    if (success) {
      context.push(AppRoutes.otpVerification);
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
                const SizedBox(height: 24),
                const Text(
                  'Create your SmartBlood Account',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 22,
                    fontWeight: FontWeight.w800,
                    color: AppColors.accent,
                    letterSpacing: -0.3,
                  ),
                ),
                const SizedBox(height: 8),
                const Text(
                  'Choose your preferred registration method',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontSize: 14,
                    color: AppColors.accentLight,
                    height: 1.4,
                  ),
                ),
                const SizedBox(height: 28),
                AuthMethodSelector(
                  selected: _method,
                  onChanged: _onMethodChanged,
                ),
                const SizedBox(height: 28),
                CustomTextField(
                  label: 'First name',
                  controller: _firstNameController,
                  validator: (v) => Validators.required(v, field: 'First name'),
                ),
                const SizedBox(height: 16),
                CustomTextField(
                  label: 'Last name',
                  controller: _lastNameController,
                  validator: (v) => Validators.required(v, field: 'Last name'),
                ),
                const SizedBox(height: 16),
                if (!_noMiddleName)
                  CustomTextField(
                    label: 'Middle name',
                    controller: _middleNameController,
                  ),
                if (!_noMiddleName) const SizedBox(height: 16),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      'No middle name',
                      style: TextStyle(color: AppColors.accent),
                    ),
                    Switch(
                      value: _noMiddleName,
                      onChanged: (v) => setState(() => _noMiddleName = v),
                    ),
                  ],
                ),
                const SizedBox(height: 16),
                AnimatedSwitcher(
                  duration: const Duration(milliseconds: 300),
                  switchInCurve: Curves.easeOutCubic,
                  switchOutCurve: Curves.easeInCubic,
                  transitionBuilder: (child, animation) {
                    return FadeTransition(
                      opacity: animation,
                      child: SlideTransition(
                        position: Tween<Offset>(
                          begin: const Offset(0, 0.06),
                          end: Offset.zero,
                        ).animate(animation),
                        child: child,
                      ),
                    );
                  },
                  child: _method == AuthContactMethod.email
                      ? CustomTextField(
                          key: const ValueKey('email'),
                          label: 'Email address',
                          controller: _emailController,
                          keyboardType: TextInputType.emailAddress,
                          validator: Validators.email,
                        )
                      : CustomTextField(
                          key: const ValueKey('phone'),
                          label: 'Phone number',
                          controller: _phoneController,
                          keyboardType: TextInputType.phone,
                          validator: Validators.phone,
                          prefixIcon: const Padding(
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
                          inputFormatters: [
                            FilteringTextInputFormatter.digitsOnly,
                            LengthLimitingTextInputFormatter(10),
                          ],
                        ),
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
                const SizedBox(height: 32),
                PrimaryButton(
                  label: 'Create Account',
                  isLoading: vm.isLoading,
                  onPressed: _register,
                ),
                const SizedBox(height: 16),
                TextLinkButton(
                  label: "I'll do it later",
                  onPressed: () => context.go(AppRoutes.login),
                ),
                const SizedBox(height: 24),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
