# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

- No pending changes

## 7.0.0 - 2018-12-02
### Added

- Add `enableCustomGrants()` method to the `VisaServiceProvider` to make adding custom grants easier
- Allow switching off the built-in Passport error handling with `Visa::disablePassportErrorHandling()`
- Replace `AccessTokenController` to use the replacement `HandlesOAuthErrors` trait
- Replace `ApproveAuthorizationController` to use the replacement `HandlesOAuthErrors` trait
- Replace `AuthorizationController` to use the replacement `HandlesOAuthErrors` trait
- Add a replacement `HandlesOAuthErrors` trait that can toggle error handling
- Add `CheckFirstPartyClient` middleware to prevent non-first-party clients from accessing assigned endpoints
- Allow switching to UUIDs instead of random strings for client IDs with `Visa::enableClientUUIDs()`
- Replace `Client` class to set `$incrementing` to `false` in support of string or UUID client IDs
- Replace `ClientRepository` class to support creating clients with random strings or UUIDs
- Replace migrations to support string or UUID client IDs

[Unreleased]: https://github.com/stratedge/visa/compare/v7.0.0...HEAD
