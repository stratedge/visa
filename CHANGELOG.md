# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## Unreleased
### Added

- Add `CheckFirstPartyClient` middleware to prevent non-first-party clients from accessing assigned endpoints
- Allow switching to UUIDs instead of random strings for client IDs with `Visa::enableClientUUIDs()`
- Replace `Client` class to set `$incrementing` to `false` in support of string or UUID client IDs
- Replace `ClientRepository` class to support creating clients with random strings or UUIDs
- Replace migrations to support string or UUID client IDs
