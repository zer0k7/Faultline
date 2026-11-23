# Screenshot placeholders

These files reserve the screenshot slots in the main README. Replace each SVG with a real PNG capture at the listed viewport. Keep the file stem or update the image paths in the README.

| Placeholder | Viewport | Suggested page |
| --- | --- | --- |
| `lab-guide-1440x900.svg` | 1440 x 900 | Lab Guide at `/lab/` with a category and progress visible |
| `dashboard-1440x900.svg` | 1440 x 900 | Student dashboard after a demo sign in |
| `course-catalog-1440x900.svg` | 1440 x 900 | Course catalog with search results and a course card |
| `mobile-lab-375x812.svg` | 375 x 812 | Lab Guide with the mobile navigation closed |
| `demo-video-1280x720.svg` | 1280 x 720 | Demo video cover card |

## Capture rules

- Run the Docker stack and use `http://127.0.0.1:8080`.
- Use the seeded demo data only.
- Capture at 100 percent browser zoom.
- Keep the persistent lab banner visible.
- Do not include browser chrome, personal data, or real credentials.
- Check the capture at 320, 375, 768, 1024, and 1440 pixels wide.
- Keep file size reasonable. PNG is fine for interface shots.

## Replace a placeholder

1. Save the capture as `assets/screens/<name>.png`.
2. Update the matching `img` or link path in `README.md`.
3. Remove the old SVG only after the new image renders.
