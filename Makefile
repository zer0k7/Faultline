.PHONY: up down reset logs smoke

up:
	docker compose up -d --build

down:
	docker compose down

reset:
	docker compose down -v
	docker compose up -d --build

logs:
	docker compose logs -f

smoke:
	sh scripts/smoke.sh
