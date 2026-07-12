import 'dart:convert';

import 'package:http/http.dart' as http;

import '../../config/constants.dart';

class ApiException implements Exception {
  ApiException(this.message, {this.statusCode});

  final String message;
  final int? statusCode;

  @override
  String toString() => message;
}

class ApiService {
  ApiService({http.Client? client, String? baseUrl})
      : _client = client ?? http.Client(),
        _baseUrl = baseUrl ?? AppConstants.apiBaseUrl;

  final http.Client _client;
  final String _baseUrl;

  Future<dynamic> get(
    String path, {
    String? token,
  }) async {
    final response = await _client.get(
      Uri.parse('$_baseUrl$path'),
      headers: _headers(token),
    );

    return _decodeResponse(response);
  }

  Future<dynamic> post(
    String path, {
    Map<String, dynamic>? body,
    String? token,
  }) async {
    final response = await _client.post(
      Uri.parse('$_baseUrl$path'),
      headers: _headers(token),
      body: jsonEncode(body ?? {}),
    );

    return _decodeResponse(response);
  }

  Map<String, String> _headers(String? token) {
    return {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      if (token != null && token.isNotEmpty) 'Authorization': 'Bearer $token',
    };
  }

  dynamic _decodeResponse(http.Response response) {
    dynamic data = {};

    if (response.body.isNotEmpty) {
      data = jsonDecode(response.body);
    }

    if (response.statusCode >= 200 && response.statusCode < 300) {
      return data;
    }

    final message = data is Map<String, dynamic>
        ? data['message']?.toString()
        : null;

    throw ApiException(
      message ?? 'Something went wrong. Please try again.',
      statusCode: response.statusCode,
    );
  }
}
