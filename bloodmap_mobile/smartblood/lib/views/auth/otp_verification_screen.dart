import 'dart:async';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../config/app_colors.dart';
import '../../config/app_routes.dart';
import '../../config/constants.dart';
import '../../viewmodels/auth_viewmodel.dart';
import '../../widgets/common/auth_method_selector.dart';
import '../../widgets/common/auth_header.dart';
import '../../widgets/common/primary_button.dart';

class OtpVerificationScreen extends StatefulWidget {
  const OtpVerificationScreen({super.key});

  @override
  State<OtpVerificationScreen> createState() => _OtpVerificationScreenState();
}

class _OtpVerificationScreenState extends State<OtpVerificationScreen> {
  final List<TextEditingController> _controllers = List.generate(
    AppConstants.otpLength,
    (_) => TextEditingController(),
  );
  final List<FocusNode> _focusNodes = List.generate(
    AppConstants.otpLength,
    (_) => FocusNode(),
  );
  int _countdown = AppConstants.otpResendSeconds;
  Timer? _timer;

  @override
  void initState() {
    super.initState();
    _startTimer();
  }

  void _startTimer() {
    _timer?.cancel();
    _countdown = AppConstants.otpResendSeconds;
    _timer = Timer.periodic(const Duration(seconds: 1), (timer) {
      if (_countdown > 0) {
        setState(() => _countdown--);
      } else {
        timer.cancel();
      }
    });
  }

  @override
  void dispose() {
    _timer?.cancel();
    for (final c in _controllers) {
      c.dispose();
    }
    for (final f in _focusNodes) {
      f.dispose();
    }
    super.dispose();
  }

  String get _otp => _controllers.map((c) => c.text).join();

  Future<void> _verify() async {
    final vm = context.read<AuthViewModel>();
    final success = await vm.verifyOtp(_otp);
    if (!mounted) return;

    if (success) {
      context.go(AppRoutes.main);
    } else if (vm.error != null) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(SnackBar(content: Text(vm.error!)));
    }
  }

  @override
  Widget build(BuildContext context) {
    final vm = context.watch<AuthViewModel>();
    final isEmail = vm.verificationMethod == AuthContactMethod.email;
    final destination = isEmail
        ? (vm.pendingEmail ?? 'your email')
        : (vm.pendingPhone ?? '+63 917 *** ****');

    return Scaffold(
      backgroundColor: AppColors.surface,
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 24),
          child: Column(
            children: [
              AuthHeader(showBack: true, onBack: () => context.pop()),
              const SizedBox(height: 48),
              const Text(
                'Verification Code',
                style: TextStyle(
                  fontSize: 22,
                  fontWeight: FontWeight.bold,
                  color: AppColors.accent,
                ),
              ),
              const SizedBox(height: 16),
              Text(
                isEmail
                    ? 'We sent a verification code to $destination'
                    : 'We sent a message with a code via SMS to $destination',
                textAlign: TextAlign.center,
                style: const TextStyle(
                  fontSize: 14,
                  color: AppColors.accentLight,
                  height: 1.5,
                ),
              ),
              const SizedBox(height: 24),
              if (vm.otpStatusMessage != null)
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    if (vm.isSendingOtp)
                      const SizedBox(
                        width: 20,
                        height: 20,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                          color: AppColors.primary,
                        ),
                      ),
                    if (vm.isSendingOtp) const SizedBox(width: 12),
                    Text(
                      vm.otpStatusMessage!,
                      style: const TextStyle(
                        fontSize: 14,
                        color: AppColors.primary,
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                  ],
                ),
              const SizedBox(height: 24),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: List.generate(AppConstants.otpLength, (index) {
                  return Container(
                    width: 56,
                    height: 64,
                    margin: const EdgeInsets.symmetric(horizontal: 8),
                    alignment: Alignment.center,
                    child: TextField(
                      controller: _controllers[index],
                      focusNode: _focusNodes[index],
                      textAlign: TextAlign.center,
                      textAlignVertical: TextAlignVertical.center,
                      keyboardType: TextInputType.number,
                      maxLength: 1,
                      style: const TextStyle(
                        fontSize: 28,
                        fontWeight: FontWeight.bold,
                        color: AppColors.accent,
                        height: 1.0,
                      ),
                      inputFormatters: [FilteringTextInputFormatter.digitsOnly],
                      decoration: InputDecoration(
                        counterText: '',
                        enabledBorder: UnderlineInputBorder(
                          borderSide: BorderSide(
                            color: _controllers[index].text.isNotEmpty
                                ? AppColors.primary
                                : AppColors.border,
                            width: _focusNodes[index].hasFocus ? 2.5 : 1,
                          ),
                        ),
                        focusedBorder: const UnderlineInputBorder(
                          borderSide: BorderSide(
                            color: AppColors.primary,
                            width: 2.5,
                          ),
                        ),
                        contentPadding: const EdgeInsets.symmetric(
                          vertical: 16,
                        ),
                      ),
                      onChanged: (value) {
                        setState(() {});
                        if (value.isNotEmpty &&
                            index < AppConstants.otpLength - 1) {
                          WidgetsBinding.instance.addPostFrameCallback((_) {
                            _focusNodes[index + 1].requestFocus();
                          });
                        }
                        if (_otp.length == AppConstants.otpLength) {
                          _verify();
                        }
                      },
                    ),
                  );
                }),
              ),
              const SizedBox(height: 24),
              Text(
                _countdown > 0
                    ? 'You can request another code in: 00:${_countdown.toString().padLeft(2, '0')}'
                    : 'Didn\'t receive the code?',
                style: const TextStyle(
                  fontSize: 13,
                  color: AppColors.accentLight,
                ),
              ),
              if (_countdown == 0)
                TextButton(
                  onPressed: () async {
                    final success = await context
                        .read<AuthViewModel>()
                        .sendOtp();
                    if (success) {
                      _startTimer();
                    }
                  },
                  child: const Text('Resend Code'),
                ),
              const Spacer(),
              PrimaryButton(
                label: 'Verify',
                isLoading: vm.isLoading,
                onPressed: _otp.length == AppConstants.otpLength
                    ? _verify
                    : null,
              ),
              const SizedBox(height: 32),
            ],
          ),
        ),
      ),
    );
  }
}
