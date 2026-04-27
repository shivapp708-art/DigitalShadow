from pydantic_settings import BaseSettings

class Settings(BaseSettings):
    APP_URL: str = "https://mydigitalshadow.in"
    API_URL: str = "https://api.mydigitalshadow.in"

    DATABASE_URL: str = "postgresql+psycopg2://mds_user:password@postgres:5432/mydigitalshadow"
    REDIS_URL:    str = "redis://redis:6379/0"

    OLLAMA_URL:   str = "http://ollama:11434"
    OLLAMA_MODEL: str = "mistral:7b"
    GEMINI_API_KEY: str = ""

    PROXY_LIST:     str = ""
    PROXY_USERNAME: str = ""
    PROXY_PASSWORD: str = ""

    class Config:
        env_file = ".env"
        extra = "ignore"

settings = Settings()
