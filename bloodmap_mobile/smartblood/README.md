# BloodMap PH - Smart Blood Bank (Flutter App)

BloodMap PH is a centralized blood inventory tracking, component-aware emergency requests, geolocation donor matching, and facility-to-PRC escalation system for blood banks. This is the Flutter mobile application for end users (donors and recipients).

## Features

- User registration via email or phone number
- Email/phone OTP verification
- Login with email/phone and password
- Profile management
- Blood donor registration
- Emergency blood requests
- Nearby facility and donor matching
- Real-time notifications

## Getting Started

### Prerequisites

- Flutter SDK (3.x+)
- Dart SDK
- Android Studio / Xcode (for mobile builds)
- Chrome (for web builds)

### Installation

1. Navigate to the project directory:
```powershell
cd bloodmap_mobile/smartblood
```

2. Install dependencies:
```powershell
flutter pub get
```

3. Run the app:
   - On Chrome (web):
     ```powershell
     flutter run -d chrome
     ```
   - On Android emulator/device:
     ```powershell
     flutter run
     ```
   - On iOS simulator/device (macOS only):
     ```powershell
     flutter run -d ios
     ```

### Configuration

Update `lib/config/constants.dart` to set the API base URL:

- For Android emulators: Use `http://10.0.2.2:8000/api/v1`
- For physical devices: Use your computer's Wi-Fi IP (e.g., `http://192.168.100.73:8000/api/v1`)
- For web: Use `http://localhost:8000/api/v1`

You can also override it at runtime with:
```powershell
flutter run --dart-define=API_BASE_URL=http://your-ip:8000/api/v1
```

## Project Structure

```
smartblood/
├── lib/
│   ├── config/          # App constants and configuration
│   ├── core/            # Core services and utilities
│   │   ├── animations/  # Page transitions
│   │   ├── services/    # API and storage services
│   │   └── utils/       # Validators and helpers
│   ├── data/            # Data models and mock data
│   ├── viewmodels/      # State management (ChangeNotifier)
│   ├── views/           # UI screens
│   └── widgets/         # Reusable widgets
└── README.md
```

## Technologies

- **Framework:** Flutter
- **State Management:** Provider (ChangeNotifier)
- **Navigation:** GoRouter
- **HTTP Client:** http package
- **Storage:** SharedPreferences
- **Icons:** Material Icons

## License

Educational / Capstone Project - BloodMap PH
