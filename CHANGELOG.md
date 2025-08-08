# Changelog

## [Unreleased]
- Add chunked media upload support
- Expand campaign DTOs and validations
- More examples and docs

## [1.0.0] - 2025-08-08
- Initial release with PSR-18 client, REST/v2 support, OAuth scaffolding
- DTOs (Profile, Organization, Post, Comment)
- Resources: Me, Posts (REST get), UgcPosts (create), SocialActions, Organizations, OrganizationsLookup, OrganizationsFollowerStats
- Ads: AdAccounts, AdCampaignGroups, AdCampaigns
- Media: initialize image/video/document uploads and binary PUT
- Retry/backoff, pagination iterator, URN helpers
- Tests and GitHub Actions CI
