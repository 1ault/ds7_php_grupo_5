export const assets = {
    load: {
        async fondo({ gameData, name })
        {
            const response = await fetch("/api/load_fondo.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json; charset=utf-8"
                },
                body: JSON.stringify(name)
            });

            if (!response.ok) {
                throw new Error(`Error: ${response.status}`);
            }

            const data = await response.json();

            const image = new Image();

            image.onload = () => {
                gameData.context.drawImage(image, 0, 0);
            }

            image.src = data.sprite;
        }
  }
};
