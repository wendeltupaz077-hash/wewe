import 'package:flutter_test/flutter_test.dart';
import 'package:smartblood/app.dart';

void main() {
  testWidgets('Blood Map PH app loads', (WidgetTester tester) async {
    await tester.pumpWidget(const BloodMapApp());
    await tester.pump();
    expect(find.text('Blood Map PH'), findsOneWidget);
  });
}
