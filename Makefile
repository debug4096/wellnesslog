USER ?= devuser

shell:
	docker exec -u $(USER) -it wellnesslogtest-php-1 bash
